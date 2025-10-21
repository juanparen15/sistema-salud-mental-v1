<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // $this->command->info('🧹 Limpiando sistema de permisos obsoleto...');

        // 1. Limpiar todas las asignaciones existentes
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();

        // $this->command->info('   ✓ Asignaciones de permisos eliminadas');

        // 2. Eliminar roles y permisos existentes
        DB::table('roles')->delete();
        DB::table('permissions')->delete();

        // $this->command->info('   ✓ Roles y permisos obsoletos eliminados');

        // 3. Actualizar estructura de tabla permissions si es necesario
        if (!Schema::hasColumn('permissions', 'display_name')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('display_name')->nullable()->after('name');
                $table->text('description')->nullable()->after('display_name');
                $table->string('category')->nullable()->after('description');
                $table->boolean('is_system')->default(false)->after('category');
            });
        }

        // 4. Actualizar estructura de tabla roles si es necesario
        if (!Schema::hasColumn('roles', 'display_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('display_name')->nullable()->after('name');
                $table->text('description')->nullable()->after('display_name');
                $table->string('color', 7)->default('#6366f1')->after('description');
                $table->boolean('is_active')->default(true)->after('color');
            });
        }

        // $this->command->info('   ✓ Estructura de tablas actualizada');

        // 5. Limpiar cache de permisos
        if (function_exists('app')) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        }

        // $this->command->info('   ✓ Cache de permisos limpiado');
        // $this->command->warn('⚠️  Ejecute: php artisan db:seed --class=RolePermissionSeeder para restaurar permisos');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // $this->command->warn('⚠️  Esta migración no puede revertirse automáticamente');
        // $this->command->info('   Debe restaurar manualmente desde un backup si es necesario');
    }
};