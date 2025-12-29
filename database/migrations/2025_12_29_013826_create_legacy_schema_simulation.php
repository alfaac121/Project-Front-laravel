<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Cuentas (Auth)
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // 2. Usuarios (Profile)
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_id')->constrained('cuentas')->onDelete('cascade');
            $table->string('nickname');
            $table->integer('rol_id')->default(3); // 3: Prosumer
            $table->integer('estado_id')->default(1); // 1: Activo
            $table->string('imagen')->nullable()->default('default.png');
            $table->text('descripcion')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });

        // 3. Productos
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->decimal('precio', 10, 2);
            $table->integer('disponibles')->default(1);
            $table->foreignId('vendedor_id')->constrained('usuarios')->onDelete('cascade');
            $table->integer('subcategoria_id')->default(1); // Simulado
            $table->integer('integridad_id')->default(1);   // Simulado
            $table->integer('estado_id')->default(1);
            $table->timestamps();
        });

        // 4. Favoritos (Usuario -> Vendedor)
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('votante_id')->constrained('usuarios')->onDelete('cascade'); // Buyer
            $table->foreignId('votado_id')->constrained('usuarios')->onDelete('cascade');  // Seller
            $table->timestamps();
            $table->unique(['votante_id', 'votado_id']);
        });

        // 5. Login IP (Session Control)
        Schema::create('login_ip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->timestamp('last_activity')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_ip');
        Schema::dropIfExists('favoritos');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('cuentas');
    }
};
