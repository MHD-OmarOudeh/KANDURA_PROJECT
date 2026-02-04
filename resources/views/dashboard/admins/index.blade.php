<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins Management - Kandura Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .main-content {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 40px;
        }

        .page-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 28px;
            color: #2d3748;
        }

        .add-btn {
            padding: 12px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s;
        }

        .add-btn:hover {
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        thead th {
            color: white;
            padding: 18px;
            text-align: right;
            font-weight: 600;
        }

        tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f7fafc;
        }

        tbody td {
            padding: 16px 18px;
            text-align: right;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-active {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge-inactive {
            background: #fed7d7;
            color: #742a2a;
        }

        .badge-super-admin {
            background: #ffd700;
            color: #744210;
        }

        .badge-admin {
            background: #bee3f8;
            color: #2c5282;
        }

        .actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-edit {
            background: #4299e1;
            color: white;
        }

        .btn-edit:hover {
            background: #3182ce;
        }

        .btn-view {
            background: #48bb78;
            color: white;
        }

        .btn-view:hover {
            background: #38a169;
        }

        .btn-delete {
            background: #fc8181;
            color: white;
        }

        .btn-delete:hover {
            background: #f56565;
        }

        .btn-toggle {
            display: none;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #fc8181;
            transition: .4s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: #48bb78;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        .toggle-slider:hover {
            box-shadow: 0 0 1px #667eea;
        }

        .btn-delete:hover {
            background: #f56565;
        }

        .pagination {
            display: flex;
            justify-content: center;
            padding: 30px 0;
            gap: 8px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-decoration: none;
            color: #4a5568;
        }

        .pagination .active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>Dashboard - Kandura Store</h1>
            <a href="{{ route('dashboard.index') }}" class="back-btn">Back to Dashboard</a>
        </div>
    </header>

    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">Admins Management</h2>
            <a href="{{ route('dashboard.admins.create') }}" class="add-btn">+ Add New Admin</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="table-container">
            @if($admins->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $index => $admin)
                            <tr>
                                <td>{{ $admins->firstItem() + $index }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->phone }}</td>
                                <td>
                                    @if($admin->hasRole('super_admin'))
                                        <span class="badge badge-super-admin">Super Admin</span>
                                    @else
                                        <span class="badge badge-admin">Admin</span>
                                    @endif
                                </td>
                                <td>
                                    @if($admin->id !== auth()->id())
                                        <form action="{{ route('dashboard.admins.toggle', $admin->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('PATCH')
                                            <label class="toggle-switch">
                                                <input type="checkbox"
                                                    {{ $admin->is_active ? 'checked' : '' }}
                                                    onchange="this.form.submit()">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </form>
                                    @else
                                        @if($admin->is_active)
                                            <span class="badge badge-active">Active</span>
                                        @else
                                            <span class="badge badge-inactive">Inactive</span>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('dashboard.admins.show', $admin->id) }}" class="btn btn-view">View</a>
                                        <a href="{{ route('dashboard.admins.edit', $admin->id) }}" class="btn btn-edit">Edit</a>

                                        @if($admin->id !== auth()->id())
                                            <form action="{{ route('dashboard.admins.destroy', $admin->id) }}" method="POST"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination">
                    {{ $admins->links() }}
                </div>
            @else
                <div class="no-data">
                    No admins found
                </div>
            @endif
        </div>
    </main>
</body>
</html>
