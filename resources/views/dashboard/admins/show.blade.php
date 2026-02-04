<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Details - Kandura Store</title>
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
            max-width: 1200px;
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
            max-width: 1200px;
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

        .details-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .detail-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 20px;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            padding: 15px 0;
            border-bottom: 1px solid #f7fafc;
        }

        .detail-label {
            font-weight: 600;
            color: #4a5568;
        }

        .detail-value {
            color: #2d3748;
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

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .permission-item {
            padding: 12px 16px;
            background: #f7fafc;
            border-radius: 8px;
            border-left: 3px solid #667eea;
            font-size: 14px;
            color: #2d3748;
        }

        .actions-section {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .btn-danger {
            background: #fc8181;
            color: white;
        }

        .btn-danger:hover {
            background: #f56565;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>Dashboard - Kandura Store</h1>
            <a href="{{ route('dashboard.admins.index') }}" class="back-btn">Back to List</a>
        </div>
    </header>

    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">Admin Details</h2>
            <a href="{{ route('dashboard.admins.edit', $admin->id) }}" class="btn btn-primary">Edit Admin</a>
        </div>

        <div class="details-container">
            <!-- Basic Information -->
            <div class="detail-section">
                <h3 class="section-title">Basic Information</h3>

                <div class="detail-row">
                    <div class="detail-label">Name:</div>
                    <div class="detail-value">{{ $admin->name }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">{{ $admin->email }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Phone:</div>
                    <div class="detail-value">{{ $admin->phone }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Role:</div>
                    <div class="detail-value">
                        @if($admin->hasRole('super_admin'))
                            <span class="badge badge-super-admin">Super Admin</span>
                        @else
                            <span class="badge badge-admin">Admin</span>
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value">
                        @if($admin->is_active)
                            <span class="badge badge-active">Active</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Created At:</div>
                    <div class="detail-value">{{ $admin->created_at->format('Y-m-d H:i:s') }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Last Updated:</div>
                    <div class="detail-value">{{ $admin->updated_at->format('Y-m-d H:i:s') }}</div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="detail-section">
                <h3 class="section-title">Permissions ({{ $admin->permissions->count() }})</h3>

                @if($admin->permissions->count() > 0)
                    <div class="permissions-grid">
                        @foreach($admin->permissions as $permission)
                            <div class="permission-item">
                                {{ $permission->name }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: #718096; font-style: italic;">No specific permissions assigned</p>
                @endif
            </div>

            <!-- Actions -->
            <div class="actions-section">
                <a href="{{ route('dashboard.admins.edit', $admin->id) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('dashboard.admins.index') }}" class="btn btn-secondary">Back to List</a>

                @if($admin->id !== auth()->id())
                    <form action="{{ route('dashboard.admins.destroy', $admin->id) }}" method="POST"
                          style="display: inline;"
                          onsubmit="return confirm('Are you sure you want to delete this admin?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
