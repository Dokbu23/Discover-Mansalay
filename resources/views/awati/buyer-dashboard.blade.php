<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Awati Buyer Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --pink-50: #fff5fb;
            --pink-100: #ffe8f3;
            --pink-300: #f9a8d4;
            --pink-600: #db2777;
            --pink-700: #be185d;
            --text: #3f2140;
            --muted: #7a4d63;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            background: radial-gradient(circle at top right, #ffe9f4 0%, #fff9fc 45%, #ffffff 100%);
            min-height: 100vh;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #ffd4e8;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.9rem 1.1rem;
        }

        .brand {
            font-weight: 800;
            color: var(--pink-700);
            font-size: 1.08rem;
        }

        .brand span { color: var(--pink-600); }

        .top-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn {
            border: 0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.55rem 0.8rem;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-main {
            background: linear-gradient(135deg, var(--pink-700) 0%, var(--pink-600) 100%);
            color: #fff;
        }

        .btn-ghost {
            background: var(--pink-100);
            color: var(--pink-700);
        }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 1.1rem;
        }

        .hero {
            background: linear-gradient(120deg, #be185d 0%, #db2777 55%, #f472b6 100%);
            color: white;
            border-radius: 22px;
            padding: 1.4rem;
            box-shadow: 0 20px 45px rgba(190, 24, 93, 0.24);
        }

        .hero h1 {
            font-size: 1.55rem;
            line-height: 1.25;
            margin-bottom: 0.35rem;
        }

        .hero p {
            opacity: 0.95;
            font-size: 0.9rem;
            max-width: 760px;
        }

        .stats {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.7rem;
        }

        .stat {
            background: #fff;
            border: 1px solid #ffd6ea;
            border-radius: 14px;
            padding: 0.8rem;
        }

        .stat .label {
            color: var(--muted);
            font-size: 0.75rem;
        }

        .stat .value {
            color: var(--pink-700);
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 0.2rem;
        }

        .grid {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 0.9rem;
        }

        .card {
            background: #fff;
            border: 1px solid #ffe1ee;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(219, 39, 119, 0.08);
            overflow: hidden;
        }

        .card-head {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #f9d8e8;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-head h2 {
            font-size: 1rem;
            color: var(--pink-700);
        }

        .card-body { padding: 0.9rem 1rem; }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.83rem;
        }

        .orders-table th,
        .orders-table td {
            text-align: left;
            padding: 0.55rem 0.35rem;
            border-bottom: 1px solid #f7deeb;
        }

        .orders-table th { color: var(--muted); font-weight: 600; }

        .status {
            display: inline-block;
            border-radius: 999px;
            padding: 0.18rem 0.55rem;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status.pending,
        .status.confirmed,
        .status.processing { background: #fff3cd; color: #856404; }
        .status.shipped { background: #dbeafe; color: #1e40af; }
        .status.delivered { background: #dcfce7; color: #166534; }
        .status.cancelled { background: #fee2e2; color: #991b1b; }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.7rem;
        }

        .product {
            border: 1px solid #f5d4e7;
            border-radius: 12px;
            padding: 0.7rem;
            background: #fffafb;
        }

        .product h4 {
            font-size: 0.88rem;
            margin-bottom: 0.2rem;
            color: #4f2240;
        }

        .product p {
            color: var(--muted);
            font-size: 0.75rem;
            margin-bottom: 0.35rem;
        }

        .product .price {
            color: var(--pink-700);
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.45rem;
        }

        @media (max-width: 980px) {
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 560px) {
            .products-grid { grid-template-columns: 1fr; }
            .hero h1 { font-size: 1.3rem; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="brand">Awati <span>Buyer</span></div>
        <div class="top-actions">
            <a href="{{ route('products.index') }}" class="btn btn-ghost">Browse Products</a>
            <a href="{{ route('orders.index') }}" class="btn btn-main">My Orders</a>
        </div>
    </div>

    <div class="container">
        <section class="hero">
            <h1>Welcome, {{ auth()->user()->name }}!</h1>
            <p>Track your Awati product orders, monitor statuses, and discover new items from trusted local vendors.</p>
        </section>

        <section class="stats">
            <div class="stat">
                <div class="label">Total Orders</div>
                <div class="value">{{ $totalOrders }}</div>
            </div>
            <div class="stat">
                <div class="label">Active Orders</div>
                <div class="value">{{ $pendingOrders }}</div>
            </div>
            <div class="stat">
                <div class="label">Delivered</div>
                <div class="value">{{ $deliveredOrders }}</div>
            </div>
            <div class="stat">
                <div class="label">Total Spent</div>
                <div class="value">PHP {{ number_format($totalSpent, 2) }}</div>
            </div>
        </section>

        <section class="grid">
            <div class="card">
                <div class="card-head">
                    <h2>Recent Orders</h2>
                    <a href="{{ route('orders.index') }}" class="btn btn-ghost" style="padding: 0.35rem 0.6rem; font-size: 0.75rem;">View all</a>
                </div>
                <div class="card-body">
                    @if($recentOrders->isEmpty())
                        <p style="color: #7a4d63; font-size: 0.9rem;">No orders yet. Start shopping from Awati products.</p>
                    @else
                        <div style="overflow-x: auto;">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>{{ optional($order->product)->name ?? 'Product removed' }}</td>
                                            <td>{{ $order->quantity }}</td>
                                            <td>PHP {{ number_format($order->total_price, 2) }}</td>
                                            <td>
                                                <span class="status {{ $order->status }}">{{ $order->status }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <h2>Suggested Products</h2>
                </div>
                <div class="card-body">
                    @if($recommendedProducts->isEmpty())
                        <p style="color: #7a4d63; font-size: 0.9rem;">No available products right now.</p>
                    @else
                        <div class="products-grid">
                            @foreach($recommendedProducts as $product)
                                <div class="product">
                                    <h4>{{ $product->name }}</h4>
                                    <p>{{ optional($product->vendor)->name ?? 'Local Vendor' }}</p>
                                    <div class="price">PHP {{ number_format($product->price, 2) }}</div>
                                    <a class="btn btn-main" style="padding: 0.35rem 0.6rem; font-size: 0.75rem;" href="{{ route('products.show', $product) }}">View</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</body>
</html>
