<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'name' => fake()->randomElement([
                'Revisar propuesta comercial',
                'Actualizar inventario',
                'Preparar reporte mensual',
                'Llamar a proveedor',
                'Diseñar banner promocional',
                'Corregir errores del sitio web',
                'Agendar reunión con cliente',
                'Publicar contenido en redes sociales',
                'Configurar campaña de correo',
                'Validar facturas pendientes',
            ]),
            'description' => fake()->randomElement([
                'Revisar los detalles con el equipo antes de entregar.',
                'Pendiente de confirmación por parte del cliente.',
                'Dar seguimiento y documentar los avances.',
                'Coordinar con el área de diseño.',
                'Enviar resultados al responsable del proyecto.',
            ]),
            'is_completed' => false,
            'start_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'expired_at' => fake()->optional()->dateTimeBetween('now', '+1 week'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_completed' => false,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_completed' => true,
        ]);
    }
}
