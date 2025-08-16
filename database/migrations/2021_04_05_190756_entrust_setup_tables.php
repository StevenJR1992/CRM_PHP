<?php

use App\Classes\PermsSeed;
use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned()->nullable()->default(null);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('role_user', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('module_name')->nullable()->default(null);
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->bigInteger('permission_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        if (app_type() == 'non-saas') {
            $company = Company::where('is_global', 0)->first();

            // Crear rol Admin
            $adminRoleId = DB::table('roles')->insertGetId([
                'company_id'   => $company->id,
                'name'         => 'admin',
                'display_name' => 'Admin',
                'description'  => 'Admin is allowed to manage everything of the app.'
            ]);

            // Crear usuario Admin
            $adminId = DB::table('users')->insertGetId([
                'company_id' => $company->id,
                'name'       => 'Admin',
                'email'      => 'admin@example.com',
                'password'   => bcrypt('12345678'),
                'role_id'    => $adminRoleId,
                'user_type'  => 'staff_members',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('role_user')->insert([
                'user_id' => $adminId,
                'role_id' => $adminRoleId,
            ]);

            // Crear Manager
            $managerRoleId = DB::table('roles')->insertGetId([
                'company_id'   => $company->id,
                'name'         => 'manager',
                'display_name' => 'Manager',
                'description'  => 'Manager of the app.'
            ]);

            $managerId = DB::table('users')->insertGetId([
                'company_id' => $company->id,
                'name'       => 'Manager',
                'email'      => 'manager@example.com',
                'password'   => bcrypt('12345678'),
                'role_id'    => $managerRoleId,
                'user_type'  => 'staff_members',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('role_user')->insert([
                'user_id' => $managerId,
                'role_id' => $managerRoleId,
            ]);

            // Crear Member
            $memberRoleId = DB::table('roles')->insertGetId([
                'company_id'   => $company->id,
                'name'         => 'member',
                'display_name' => 'Member',
                'description'  => 'Member of the app.'
            ]);

            $memberId = DB::table('users')->insertGetId([
                'company_id' => $company->id,
                'name'       => 'Member',
                'email'      => 'member@example.com',
                'password'   => bcrypt('12345678'),
                'role_id'    => $memberRoleId,
                'user_type'  => 'staff_members',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('role_user')->insert([
                'user_id' => $memberId,
                'role_id' => $memberRoleId,
            ]);
        }


        // Seeding Permissions
        PermsSeed::seedMainPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('permission_role');
        Schema::drop('permissions');
        Schema::drop('role_user');
        Schema::drop('roles');
    }
}
