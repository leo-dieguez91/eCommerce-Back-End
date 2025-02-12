<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Orden</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Estado de tu Pedido</h1>
            
            <div class="mb-6">
                <p class="text-gray-600">Número de seguimiento:</p>
                <p class="text-lg font-semibold">{{ $order['tracking_number'] }}</p>
            </div>

            <div class="mb-6">
                <p class="text-gray-600">Estado:</p>
                <p class="text-lg font-semibold">
                    @switch($order['status'])
                        @case('pending')
                            <span class="text-yellow-600">Pendiente</span>
                            @break
                        @case('processing')
                            <span class="text-blue-600">Procesando</span>
                            @break
                        @case('completed')
                            <span class="text-green-600">Completado</span>
                            @break
                        @default
                            <span class="text-gray-600">{{ $order['status'] }}</span>
                    @endswitch
                </p>
            </div>

            <div class="mb-6">
                <p class="text-gray-600">Fecha de creación:</p>
                <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</body>
</html> 