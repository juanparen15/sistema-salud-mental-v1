<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonthlyFollowupResource\Pages;
use App\Models\MonthlyFollowup;
use App\Models\Patient;
use App\Models\MentalDisorder;
use App\Models\SuicideAttempt;
use App\Models\SubstanceConsumption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;

class MonthlyFollowupResource extends Resource
{
    protected static ?string $model = MonthlyFollowup::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Seguimientos Mensuales';
    protected static ?string $modelLabel = 'Seguimiento Mensual';
    protected static ?string $pluralModelLabel = 'Seguimientos Mensuales';
    protected static ?string $navigationGroup = 'Gestión de Pacientes';
    protected static ?int $navigationSort = 1;

    // ============================================
    // FORMULARIO
    // ============================================
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Seguimiento')
                    ->schema([
                        Forms\Components\Select::make('followupable_type')
                            ->label('Tipo de Caso')
                            ->options([
                                MentalDisorder::class => 'Trastorno Mental',
                                SuicideAttempt::class => 'Intento de Suicidio',
                                SubstanceConsumption::class => 'Consumo SPA',
                            ])
                            ->required()
                            ->live()
                            ->disabled(fn() => (bool)request()->query('source_type'))
                            ->default(function () {
                                $sourceType = request()->query('source_type');
                                return match ($sourceType) {
                                    'mental_disorder' => MentalDisorder::class,
                                    'suicide_attempt' => SuicideAttempt::class,
                                    'substance_consumption' => SubstanceConsumption::class,
                                    default => null
                                };
                            })
                            ->afterStateUpdated(fn($state, $set) => $set('followupable_id', null)),

                        Forms\Components\Select::make('followupable_id')
                            ->label('Caso Específico')
                            ->options(function (callable $get) {
                                $type = $get('followupable_type');
                                if (!$type) return [];

                                $query = match ($type) {
                                    MentalDisorder::class => MentalDisorder::with('patient'),
                                    SuicideAttempt::class => SuicideAttempt::with('patient'),
                                    SubstanceConsumption::class => SubstanceConsumption::with('patient'),
                                    default => null
                                };

                                if (!$query) return [];

                                // Aplicar filtro de seguridad por usuario
                                $query = static::applyUserScopeToQuery($query);

                                return $query->get()->mapWithKeys(fn($case) => [
                                    $case->id => $case->patient->full_name . ' - ' . $case->patient->document_number
                                ]);
                            })
                            ->searchable()
                            ->required()
                            ->columnSpanFull()
                            ->disabled(fn() => (bool)request()->query('source_id'))
                            ->default(fn() => request()->query('source_id'))
                            ->placeholder('Primero selecciona el tipo de caso'),

                        Forms\Components\DatePicker::make('followup_date')
                            ->label('Fecha de Seguimiento')
                            ->required()
                            ->default(now())
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $date = \Carbon\Carbon::parse($state);
                                    $set('year', $date->year);
                                    $set('month', $date->month);
                                }
                            }),

                        Forms\Components\Hidden::make('year')
                            ->default(now()->year),

                        Forms\Components\Hidden::make('month')
                            ->default(now()->month),

                        Forms\Components\Select::make('status')
                            ->label('Estado del Seguimiento')
                            ->options([
                                'pending' => 'Pendiente',
                                'completed' => 'Completado',
                                'not_contacted' => 'No Contactado',
                                'refused' => 'Rechazado',
                            ])
                            ->default('completed')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detalles del Seguimiento')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción del Seguimiento')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Describe las actividades, observaciones y resultados del seguimiento...'),

                        Forms\Components\DatePicker::make('next_followup')
                            ->label('Próximo Seguimiento')
                            ->nullable()
                            ->helperText('Fecha programada para el siguiente seguimiento'),

                        Forms\Components\TagsInput::make('actions_taken')
                            ->label('Acciones Realizadas')
                            ->placeholder('Presiona Enter para agregar cada acción')
                            ->columnSpanFull()
                            ->helperText('Ej: "Evaluación psicológica", "Terapia individual", "Remisión a especialista"'),

                        Forms\Components\Hidden::make('performed_by')
                            ->default(auth()->id()),
                    ]),
            ])
            ->columns(1);
    }

    // ============================================
    // TABLA
    // ============================================
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient')
                    ->label('Paciente')
                    ->formatStateUsing(function ($record) {
                        if ($record->followupable && $record->followupable->patient) {
                            $patient = $record->followupable->patient;
                            return $patient->document_number . ' - ' . $patient->full_name;
                        }
                        return 'N/A';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHasMorph(
                            'followupable',
                            [MentalDisorder::class, SuicideAttempt::class, SubstanceConsumption::class],
                            function (Builder $q) use ($search) {
                                $q->whereHas('patient', function (Builder $patientQuery) use ($search) {
                                    $patientQuery->where('full_name', 'like', "%{$search}%")
                                        ->orWhere('document_number', 'like', "%{$search}%");
                                });
                            }
                        );
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->whereHasMorph(
                            'followupable',
                            [MentalDisorder::class, SuicideAttempt::class, SubstanceConsumption::class],
                            function (Builder $q) use ($direction) {
                                $q->join('patients', function ($join) {
                                    $join->on('patients.id', '=', 
                                        \DB::raw("CASE 
                                            WHEN followupables.followupable_type = '" . MentalDisorder::class . "' THEN mental_disorders.patient_id
                                            WHEN followupables.followupable_type = '" . SuicideAttempt::class . "' THEN suicide_attempts.patient_id
                                            WHEN followupables.followupable_type = '" . SubstanceConsumption::class . "' THEN substance_consumptions.patient_id
                                        END")
                                    );
                                })->orderBy('patients.full_name', $direction);
                            }
                        );
                    }),

                Tables\Columns\TextColumn::make('followupable_type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        MentalDisorder::class => 'Trastorno Mental',
                        SuicideAttempt::class => 'Intento Suicidio',
                        SubstanceConsumption::class => 'Consumo SPA',
                        default => 'Otro'
                    })
                    ->color(fn(string $state): string => match ($state) {
                        MentalDisorder::class => 'info',
                        SuicideAttempt::class => 'danger',
                        SubstanceConsumption::class => 'warning',
                        default => 'gray'
                    }),

                Tables\Columns\TextColumn::make('followup_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'completed' => 'Completado',
                        'not_contacted' => 'No Contactado',
                        'refused' => 'Rechazado',
                        default => $state
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'not_contacted' => 'info',
                        'refused' => 'danger',
                        default => 'gray'
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Realizado por')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('next_followup')
                    ->label('Próximo')
                    ->date('d/m/Y')
                    ->placeholder('No programado')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('followupable_type')
                    ->label('Tipo de Caso')
                    ->options([
                        MentalDisorder::class => 'Trastorno Mental',
                        SuicideAttempt::class => 'Intento de Suicidio',
                        SubstanceConsumption::class => 'Consumo SPA',
                    ]),

                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'completed' => 'Completado',
                        'not_contacted' => 'No Contactado',
                        'refused' => 'Rechazado',
                    ]),

                Tables\Filters\Filter::make('followup_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn(Builder $query, $date) => 
                                $query->whereDate('followup_date', '>=', $date)
                            )
                            ->when($data['until'], fn(Builder $query, $date) => 
                                $query->whereDate('followup_date', '<=', $date)
                            );
                    }),

                Tables\Filters\Filter::make('my_followups')
                    ->label('Mis Seguimientos')
                    ->query(fn(Builder $query) => $query->where('performed_by', auth()->id()))
                    ->toggle(),

                Tables\Filters\Filter::make('pending_only')
                    ->label('Solo Pendientes')
                    ->query(fn(Builder $query) => $query->where('status', 'pending'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Cambiar Estado')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Nuevo Estado')
                                ->options([
                                    'pending' => 'Pendiente',
                                    'completed' => 'Completado',
                                    'not_contacted' => 'No Contactado',
                                    'refused' => 'Rechazado',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            $count = 0;
                            foreach ($records as $record) {
                                // Verificar permisos antes de actualizar
                                if (static::canEdit($record)) {
                                    $record->update(['status' => $data['status']]);
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title("Estado actualizado en {$count} seguimientos")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('quick_create')
                    ->label('Seguimiento Rápido')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('patient_id')
                            ->label('Paciente')
                            ->options(function () {
                                $query = Patient::query();

                                // Aplicar scope de usuario
                                if (!auth()->user()->can('view_any_monthly::followup')) {
                                    $query->where('assigned_to', auth()->id());
                                }

                                return $query->get()->mapWithKeys(fn($patient) => [
                                    $patient->id => $patient->full_name . ' - ' . $patient->document_number
                                ]);
                            })
                            ->searchable()
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción Rápida')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (array $data) {
                        MonthlyFollowup::create([
                            'followupable_type' => Patient::class,
                            'followupable_id' => $data['patient_id'],
                            'followup_date' => now(),
                            'year' => now()->year,
                            'month' => now()->month,
                            'status' => 'completed',
                            'description' => $data['description'],
                            'performed_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('Seguimiento rápido creado')
                            ->success()
                            ->send();
                    })
                    ->visible(fn() => auth()->user()->can('create_monthly::followup')),
            ])
            ->defaultSort('followup_date', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    // ============================================
    // QUERY SCOPE - CONTROL DE ACCESO
    // ============================================
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['followupable.patient', 'user']);

        // Super Admin puede ver todo
        if (auth()->user()->hasRole('super_admin')) {
            return $query;
        }

        // Si tiene permiso para ver todos los seguimientos
        if (auth()->user()->can('view_any_monthly::followup')) {
            return $query;
        }

        // Solo puede ver sus propios seguimientos
        return $query->where('performed_by', auth()->id());
    }

    // ============================================
    // HELPER: APLICAR SCOPE A RELACIONES
    // ============================================
    protected static function applyUserScopeToQuery(Builder $query): Builder
    {
        // Super Admin ve todo
        if (auth()->user()->hasRole('super_admin')) {
            return $query;
        }

        // Si puede ver todos los pacientes, no aplicar filtro
        if (auth()->user()->can('view_any_patient')) {
            return $query;
        }

        // Solo puede ver casos de sus pacientes asignados
        return $query->whereHas('patient', function (Builder $q) {
            $q->where('assigned_to', auth()->id());
        });
    }

    // ============================================
    // NAVIGATION BADGE
    // ============================================
    public static function getNavigationBadge(): ?string
    {
        $query = static::getModel()::where('status', 'pending');

        // Aplicar el mismo scope que getEloquentQuery
        if (!auth()->user()->hasRole('super_admin')) {
            if (!auth()->user()->can('view_any_monthly::followup')) {
                $query->where('performed_by', auth()->id());
            }
        }

        $count = $query->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getNavigationBadge();
        
        if (!$count) return null;
        if ((int) $count > 10) return 'danger';
        if ((int) $count > 5) return 'warning';
        return 'primary';
    }

    // ============================================
    // PÁGINAS
    // ============================================
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonthlyFollowups::route('/'),
            'create' => Pages\CreateMonthlyFollowup::route('/create'),
            'edit' => Pages\EditMonthlyFollowup::route('/{record}/edit'),
            'view' => Pages\ViewMonthlyFollowup::route('/{record}'),
        ];
    }
}