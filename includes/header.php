<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Dashboard'; ?> - Pesantren Asshiddiqiyah</title>
    
    <!-- Bootstrap 5 CSS dari CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome dari CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-green: #86efac;
            --secondary-green: #4ade80;
            --dark-green: #16a34a;
            --light-green: #dcfce7;
            --cream: #fef3c7;
        }
        
        body {
            background: linear-gradient(135deg, var(--light-green) 0%, var(--cream) 50%, #fff7ed 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-green) 0%, var(--secondary-green) 50%, var(--dark-green) 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 20px;
            margin: 2px 0;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding-left: 25px;
        }
        
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
            border-left: 4px solid white;
            padding-left: 21px;
        }
        
        .sidebar .sidebar-brand {
            padding: 25px 20px;
            text-align: center;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-heading {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            padding: 15px 20px 5px;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        /* Topbar */
        .topbar {
            background: linear-gradient(135deg, white 0%, var(--cream) 100%);
            border-bottom: 2px solid var(--light-green);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        /* Cards */
        .card {
            border: 2px solid var(--light-green);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--light-green) 0%, var(--cream) 100%);
            border-bottom: 2px solid var(--primary-green);
            font-weight: 600;
            color: var(--dark-green);
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            border: none;
            color: var(--dark-green);
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-green) 0%, var(--dark-green) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(134, 239, 172, 0.4);
            color: white;
        }
        
        /* Stat Cards */
        .stat-card {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            color: var(--dark-green);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(134, 239, 172, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card i {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 2.5rem;
            opacity: 0.3;
        }
        
        /* Tables */
        .table {
            background: white;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--light-green) 0%, var(--cream) 100%);
        }
        
        .table thead th {
            color: var(--dark-green);
            font-weight: 600;
            border-bottom: 2px solid var(--primary-green);
        }
        
        .table tbody tr:hover {
            background: var(--light-green);
        }
        
        /* Badges */
        .badge-primary {
            background-color: var(--primary-green);
            color: var(--dark-green);
        }
        
        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--primary-green);
            object-fit: cover;
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            border: 2px solid var(--light-green);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        /* Modal */
        .modal-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            color: var(--dark-green);
        }
        
        /* Scroll To Top */
        .scroll-to-top {
            position: fixed;
            right: 20px;
            bottom: 20px;
            background: var(--secondary-green);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all 0.3s;
        }
        
        .scroll-to-top:hover {
            background: var(--dark-green);
            transform: translateY(-3px);
        }
        
        /* Search Bar */
        .form-control-lg {
            padding: 15px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
        }
        
        .form-control-lg:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(134, 239, 172, 0.25);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                z-index: 1050;
                transition: all 0.3s;
            }
            
            .sidebar.show {
                left: 0;
            }
        }
    </style>
</head>
<body>