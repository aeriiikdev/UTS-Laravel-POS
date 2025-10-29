<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - Point of Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #43454fff 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container-main {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
        }

        .welcome-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .welcome-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .welcome-content p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin: 30px 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
            font-size: 1rem;
        }

        .feature-item i {
            font-size: 1.5rem;
            color: #ffd700;
            width: 30px;
        }

        .btn-group-welcome {
            display: flex;
            gap: 15px;
            margin-top: 40px;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 2px solid white;
            color: white;
            padding: 12px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-login:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-register {
            background: white;
            color: #667eea;
            padding: 12px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            border: 2px solid white;
        }

        .btn-register:hover {
            background: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .welcome-image {
            text-align: center;
        }

        .card-feature {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 20px;
            color: white;
            transition: all 0.3s ease;
        }

        .card-feature:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-5px);
        }

        .card-feature i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #ffd700;
        }

        .card-feature h4 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-feature p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .welcome-section {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .welcome-content h1 {
                font-size: 2rem;
            }

            .btn-group-welcome {
                flex-direction: column;
            }

            .btn-login, .btn-register {
                width: 100%;
                text-align: center;
            }
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .logo i {
            font-size: 2.5rem;
            color: #ffd700;
        }

        .logo span {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="welcome-section">
            <div class="welcome-content">
                <div class="logo">
                    <i class="fas fa-shopping-cart"></i>
                    <span>POS System</span>
                </div>

                <h1>Kelola Toko Anda dengan Mudah</h1>
                <p>Sistem Point of Sale modern yang dirancang untuk meningkatkan efisiensi penjualan dan manajemen inventori toko Anda.</p>

                <div class="features">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Manajemen produk dan kategori yang fleksibel</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Transaksi penjualan real-time dengan pembayaran mudah</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Laporan penjualan harian, mingguan, dan bulanan</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Manajemen pengguna dan keamanan data</span>
                    </div>
                </div>

                <div class="btn-group-welcome">
                    <a href="/login" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="/register" class="btn-register">
                        <i class="fas fa-user-plus"></i> Daftar
                    </a>
                </div>
            </div>

            <div class="welcome-image">
                <div class="card-feature">
                    <i class="fas fa-chart-line"></i>
                    <h4>Analitik Penjualan</h4>
                    <p>Pantau penjualan dan keuntungan dengan dashboard interaktif</p>
                </div>

                <div class="card-feature">
                    <i class="fas fa-box"></i>
                    <h4>Manajemen Stok</h4>
                    <p>Kelola inventori produk dengan sistem tracking otomatis</p>
                </div>

                <div class="card-feature">
                    <i class="fas fa-receipt"></i>
                    <h4>Transaksi Cepat</h4>
                    <p>Proses penjualan yang cepat dan akurat</p>
                </div>

                <div class="card-feature">
                    <i class="fas fa-lock"></i>
                    <h4>Aman & Terpercaya</h4>
                    <p>Data Anda dilindungi dengan enkripsi tingkat enterprise</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>