<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🧹 Limpiando permisos existentes...');
        $this->cleanExistingPermissions();

        $this->command->info('🔑 Creando permisos del sistema...');
        $this->createSystemPermissions();

        $this->command->info('👥 Creando roles del sistema...');
        $this->createSystemRoles();

        $this->command->info('🔗 Asignando permisos a roles...');
        $this->assignPermissionsToRoles();

        $this->command->info('👤 Configurando usuarios...');
        $this->setupUsers();

        $this->command->info('✅ Sistema de roles y permisos configurado correctamente');
    }

    private function cleanExistingPermissions(): void
    {
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        Permission::query()->delete();
    }

    private function createSystemPermissions(): void
    {
        $permissions = [
            // 📊 DASHBOARD
            'view_dashboard' => [
                'display' => 'Ver Dashboard',
                'description' => 'Acceso al panel principal del sistema',
                'category' => 'dashboard'
            ],
            'view_analytics' => [
                'display' => 'Ver Analíticas',
                'description' => 'Acceso a estadísticas y métricas del sistema',
                'category' => 'dashboard'
            ],

            // 👥 PACIENTES - Niveles de acceso
            'view_patients' => [
                'display' => 'Ver Pacientes',
                'description' => 'Ver pacientes asignados al usuario',
                'category' => 'patients'
            ],
            'view_any_patients' => [
                'display' => 'Ver Todos los Pacientes',
                'description' => 'Ver pacientes de todo el sistema',
                'category' => 'patients'
            ],
            'create_patients' => [
                'display' => 'Crear Pacientes',
                'description' => 'Registrar nuevos pacientes',
                'category' => 'patients'
            ],
            'edit_patients' => [
                'display' => 'Editar Pacientes',
                'description' => 'Modificar información de pacientes',
                'category' => 'patients'
            ],
            'delete_patients' => [
                'display' => 'Eliminar Pacientes',
                'description' => 'Eliminar registros de pacientes',
                'category' => 'patients'
            ],
            'import_patients' => [
                'display' => 'Importar Pacientes',
                'description' => 'Importar datos masivos de pacientes',
                'category' => 'patients'
            ],
            'export_patients' => [
                'display' => 'Exportar Pacientes',
                'description' => 'Exportar datos de pacientes',
                'category' => 'patients'
            ],

            // 📋 SEGUIMIENTOS - Niveles granulares
            'view_followups' => [
                'display' => 'Ver Seguimientos',
                'description' => 'Ver seguimientos propios',
                'category' => 'followups'
            ],
            'view_any_followups' => [
                'display' => 'Ver Seguimientos de Asignados',
                'description' => 'Ver seguimientos de pacientes asignados',
                'category' => 'followups'
            ],
            'view_all_followups' => [
                'display' => 'Ver Todos los Seguimientos',
                'description' => 'Ver seguimientos de todo el sistema',
                'category' => 'followups'
            ],
            'create_followups' => [
                'display' => 'Crear Seguimientos',
                'description' => 'Registrar nuevos seguimientos',
                'category' => 'followups'
            ],
            'edit_followups' => [
                'display' => 'Editar Seguimientos Propios',
                'description' => 'Modificar seguimientos creados por el usuario',
                'category' => 'followups'
            ],
            'edit_all_followups' => [
                'display' => 'Editar Todos los Seguimientos',
                'description' => 'Modificar cualquier seguimiento del sistema',
                'category' => 'followups'
            ],
            'delete_followups' => [
                'display' => 'Eliminar Seguimientos',
                'description' => 'Eliminar registros de seguimientos',
                'category' => 'followups'
            ],
            'export_followups' => [
                'display' => 'Exportar Seguimientos',
                'description' => 'Exportar datos de seguimientos',
                'category' => 'followups'
            ],

            // 📈 REPORTES
            'view_reports' => [
                'display' => 'Ver Reportes',
                'description' => 'Acceso a reportes básicos',
                'category' => 'reports'
            ],
            'generate_reports' => [
                'display' => 'Generar Reportes',
                'description' => 'Crear nuevos reportes del sistema',
                'category' => 'reports'
            ],
            'export_reports' => [
                'display' => 'Exportar Reportes',
                'description' => 'Descargar reportes generados',
                'category' => 'reports'
            ],
            'view_advanced_reports' => [
                'display' => 'Reportes Avanzados',
                'description' => 'Acceso a reportes con datos sensibles',
                'category' => 'reports'
            ],

            // 👤 USUARIOS
            'user_view' => [
                'display' => 'Ver Usuarios',
                'description' => 'Ver lista de usuarios del sistema',
                'category' => 'users'
            ],
            'user_create' => [
                'display' => 'Crear Usuarios',
                'description' => 'Agregar nuevos usuarios al sistema',
                'category' => 'users'
            ],
            'user_edit' => [
                'display' => 'Editar Usuarios',
                'description' => 'Modificar información de usuarios',
                'category' => 'users'
            ],
            'user_delete' => [
                'display' => 'Eliminar Usuarios',
                'description' => 'Eliminar usuarios del sistema',
                'category' => 'users'
            ],
            'user_impersonate' => [
                'display' => 'Suplantar Usuarios',
                'description' => 'Iniciar sesión como otro usuario',
                'category' => 'users'
            ],
            'user_change_role' => [
                'display' => 'Cambiar Rol de Usuario',
                'description' => 'Modificar el rol asignado a un usuario',
                'category' => 'users'
            ],
            'user_reset_password' => [
                'display' => 'Restablecer Contraseña de Usuario',
                'description' => 'Enviar enlace de restablecimiento de contraseña',
                'category' => 'users'
            ],
            'user_activate_deactivate' => [
                'display' => 'Activar/Desactivar Usuario',
                'description' => 'Cambiar estado activo de un usuario',
                'category' => 'users'
            ],
            'user_profile_edit' => [
                'display' => 'Editar Perfil Propio',
                'description' => 'Modificar información del propio usuario',
                'category' => 'users'
            ],
            'user_change_own_password' => [
                'display' => 'Cambiar Propia Contraseña',
                'description' => 'Modificar la propia contraseña de acceso',
                'category' => 'users'
            ],
            'user_view_own_permissions' => [
                'display' => 'Ver Propios Permisos',
                'description' => 'Consultar los permisos asignados al propio usuario',
                'category' => 'users'
            ],
            'user_export' => [
                'display' => 'Exportar Usuarios',
                'description' => 'Exportar datos de usuarios',
                'category' => 'users'
            ],
            // 🔐 ROLES Y PERMISOS
            'roles_view' => [
                'display' => 'Ver Roles',
                'description' => 'Vista de roles del sistema',
                'category' => 'roles'
            ],
            'roles_create' => [
                'display' => 'Crear Roles',
                'description' => 'Agregar nuevos roles al sistema',
                'category' => 'roles'
            ],
            'roles_edit' => [
                'display' => 'Editar Roles',
                'description' => 'Modificar roles existentes',
                'category' => 'roles'
            ],
            'roles_delete' => [
                'display' => 'Eliminar Roles',
                'description' => 'Eliminar roles del sistema',
                'category' => 'roles'
            ],

            'manage_permissions' => [
                'display' => 'Gestionar Permisos',
                'description' => 'Asignar/revocar permisos a roles',
                'category' => 'roles'
            ],

            // ⚙️ SISTEMA
            'view_system_logs' => [
                'display' => 'Ver Logs del Sistema',
                'description' => 'Consultar registros de actividad',
                'category' => 'system'
            ],
            'manage_settings' => [
                'display' => 'Configuración del Sistema',
                'description' => 'Modificar configuraciones generales',
                'category' => 'system'
            ],
            'system_backup' => [
                'display' => 'Backup del Sistema',
                'description' => 'Crear respaldos de datos',
                'category' => 'system'
            ],
            'bulk_actions' => [
                'display' => 'Acciones en Lote',
                'description' => 'Operaciones masivas en el sistema',
                'category' => 'system'
            ],
        ];

        $count = 0;
        foreach ($permissions as $name => $data) {
            Permission::create([
                'name' => $name,
                'display_name' => $data['display'],
                'description' => $data['description'],
                'category' => $data['category'],
                'guard_name' => 'web',
                'is_system' => true
            ]);
            $count++;
        }

        $this->command->info("   ✓ {$count} permisos creados");
    }

    private function createSystemRoles(): void
    {
        $roles = [
            'super_admin' => [
                'display_name' => 'Super Administrador',
                'description' => 'Acceso total al sistema con todos los permisos',
                'color' => '#dc2626'
            ],
            'admin' => [
                'display_name' => 'Administrador',
                'description' => 'Administrador con permisos elevados de gestión',
                'color' => '#2563eb'
            ],
            'coordinator' => [
                'display_name' => 'Coordinador',
                'description' => 'Coordinador con permisos de supervisión',
                'color' => '#059669'
            ],
            'psychologist' => [
                'display_name' => 'Psicólogo',
                'description' => 'Profesional de salud mental',
                'color' => '#7c3aed'
            ],
            'social_worker' => [
                'display_name' => 'Trabajador Social',
                'description' => 'Profesional de trabajo social',
                'color' => '#ea580c'
            ],
            'assistant' => [
                'display_name' => 'Asistente',
                'description' => 'Asistente con permisos básicos',
                'color' => '#6b7280'
            ]
        ];

        $count = 0;
        foreach ($roles as $name => $data) {
            Role::create([
                'name' => $name,
                'display_name' => $data['display_name'],
                'description' => $data['description'],
                'color' => $data['color'],
                'guard_name' => 'web',
                'is_active' => true
            ]);
            $count++;
        }

        $this->command->info("   ✓ {$count} roles creados");
    }

    private function assignPermissionsToRoles(): void
    {
        $rolePermissions = [
            'super_admin' => '*', // Todos los permisos

            'admin' => [
                'view_dashboard',
                'view_analytics',
                'view_any_patients',
                'create_patients',
                'edit_patients',
                'delete_patients',
                'import_patients',
                'export_patients',
                'view_all_followups',
                'create_followups',
                'edit_all_followups',
                'delete_followups',
                'export_followups',
                'view_reports',
                'generate_reports',
                'export_reports',
                'view_advanced_reports',
                'manage_users',
                'view_system_logs',
                'bulk_actions'
            ],

            'coordinator' => [
                'view_dashboard',
                'view_analytics',
                'view_any_patients',
                'create_patients',
                'edit_patients',
                'export_patients',
                'view_any_followups',
                'create_followups',
                'edit_followups',
                'export_followups',
                'view_reports',
                'generate_reports',
                'export_reports',
                'bulk_actions'
            ],

            'psychologist' => [
                'view_dashboard',
                'view_patients',
                'create_patients',
                'edit_patients',
                'view_followups',
                'view_any_followups',
                'create_followups',
                'edit_followups',
                'view_reports',
                'generate_reports'
            ],

            'social_worker' => [
                'view_dashboard',
                'view_patients',
                'create_patients',
                'edit_patients',
                'view_followups',
                'view_any_followups',
                'create_followups',
                'edit_followups',
                'view_reports'
            ],

            'assistant' => [
                'view_dashboard',
                'view_patients',
                'view_followups',
                'view_reports'
            ]
        ];

        $totalAssignments = 0;

        foreach ($rolePermissions as $roleName => $permissionsList) {
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                continue;
            }

            if ($permissionsList === '*') {
                // Super admin obtiene todos los permisos
                $permissions = Permission::all();
                $role->syncPermissions($permissions);
                $totalAssignments += $permissions->count();
                $this->command->info("   ✓ {$role->display_name}: TODOS los permisos");
            } else {
                // Roles específicos obtienen permisos definidos
                $permissions = Permission::whereIn('name', $permissionsList)->get();
                $role->syncPermissions($permissions);
                $totalAssignments += $permissions->count();
                $this->command->info("   ✓ {$role->display_name}: {$permissions->count()} permisos");
            }
        }

        $this->command->info("   📊 Total asignaciones: {$totalAssignments}");
    }

    private function setupUsers(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'name' => 'Super Administrador',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now(),
            ]
        );

        if (!$superAdmin->hasRole('super_admin')) {
            $superAdmin->syncRoles(['super_admin']);
            $this->command->info('   ✓ Super Admin configurado');
        }

        // Usuarios de ejemplo
        $exampleUsers = [
            [
                'name' => 'Dr. Juan Coordinador',
                'email' => 'coordinador@sistema.com',
                'role' => 'coordinator'
            ],
            [
                'name' => 'Dra. María Psicóloga',
                'email' => 'psicologa@sistema.com',
                'role' => 'psychologist'
            ],
            [
                'name' => 'Carlos Trabajador Social',
                'email' => 'social@sistema.com',
                'role' => 'social_worker'
            ],
            [
                'name' => 'Ana Asistente',
                'email' => 'asistente@sistema.com',
                'role' => 'assistant'
            ]
        ];

        foreach ($exampleUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('demo123'),
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole($userData['role'])) {
                $user->syncRoles([$userData['role']]);
                $this->command->info("   ✓ {$userData['name']} configurado");
            }
        }

        $this->command->warn('🔑 Credenciales:');
        $this->command->warn('   Super Admin: admin@sistema.com / admin123');
        $this->command->warn('   Demos: [usuario]@sistema.com / demo123');
    }
}
