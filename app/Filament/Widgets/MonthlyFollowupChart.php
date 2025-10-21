<?php

// MonthlyFollowupChart.php - Con Permisos
namespace App\Filament\Widgets;

use App\Models\MonthlyFollowup;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyFollowupChart extends ChartWidget
{
    protected static ?string $heading = 'Seguimientos Mensuales';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';

    // ✅ Control de acceso al widget
    public static function canView(): bool
    {
        return auth()->user()->can('view_dashboard') && 
               (auth()->user()->can('view_followups') || auth()->user()->can('view_any_followups'));
    }

    protected function getData(): array
    {
        // ✅ Verificar permisos antes de mostrar datos
        if (!auth()->user()->can('view_followups') && !auth()->user()->can('view_any_followups')) {
            return [
                'datasets' => [],
                'labels' => ['Acceso Restringido'],
            ];
        }

        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->translatedFormat('F Y');
            
            $query = MonthlyFollowup::where('year', $date->year)
                ->where('month', $date->month);

            // ✅ Aplicar filtros según permisos
            if (!auth()->user()->can('view_all_followups')) {
                if (auth()->user()->can('view_any_followups')) {
                    // Solo seguimientos de sus pacientes asignados
                    $query->whereHas('followupable', function ($q) {
                        $q->whereHas('patient', function ($patientQuery) {
                            $patientQuery->where('assigned_to', auth()->id());
                        });
                    });
                } else {
                    // Solo seguimientos creados por él
                    $query->where('performed_by', auth()->id());
                }
            }
                
            $data['completed'][] = (clone $query)->where('status', 'completed')->count();
            $data['pending'][] = (clone $query)->where('status', 'pending')->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Completados',
                    'data' => $data['completed'],
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgb(34, 197, 94)',
                ],
                [
                    'label' => 'Pendientes',
                    'data' => $data['pending'],
                    'backgroundColor' => 'rgba(251, 146, 60, 0.2)',
                    'borderColor' => 'rgb(251, 146, 60)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}