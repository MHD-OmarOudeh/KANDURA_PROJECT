<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kandura Store Addresses</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8f9fa; min-height: 100vh; }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .back-btn {
            color: white;
            text-decoration: none;
            transition: transform 0.3s ease;
        }
        .back-btn:hover {
            transform: translateX(-5px);
        }
        .header-left h1 {
            font-size: 1.5em;
            font-weight: 700;
        }
        .header-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .user-name {
            font-weight: 600;
        }
        .btn-logout {
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 0.9em;
            transition: all 0.3s ease;
            cursor: pointer;
            backdrop-filter: blur(10px);
        }
        .btn-logout:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 40px;
        }

        /* Success Message */
        .success-msg {
            background: white;
            border-left: 5px solid #43e97b;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(67, 233, 123, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, transparent 100%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.15);
        }
        .stat-card:nth-child(1) { border-left: 5px solid #667eea; }
        .stat-card:nth-child(2) { border-left: 5px solid #43e97b; }
        .stat-card:nth-child(3) { border-left: 5px solid #f093fb; }
        .stat-card:nth-child(4) { border-left: 5px solid #feca57; }

        .stat-card h3 {
            font-size: 0.85em;
            color: #718096;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .stat-card .number {
            font-size: 2.5em;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-card .description {
            font-size: 0.85em;
            color: #a0aec0;
            margin-top: 5px;
        }

        /* Filters Section */
        .filters-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 0.9em;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95em;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-group {
            display: flex;
            gap: 15px;
        }
        .btn-primary {
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .btn-secondary {
            padding: 12px 30px;
            background: #e2e8f0;
            color: #2d3748;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover {
            background: #cbd5e0;
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        th {
            padding: 18px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 0.85em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        tbody tr {
            transition: all 0.3s ease;
        }
        tbody tr:hover {
            background: #f7fafc;
        }
        .user-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1em;
            flex-shrink: 0;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }
        .badge-blue {
            background: #eef2ff;
            color: #667eea;
        }
        .badge-green {
            background: #e6fffa;
            color: #43e97b;
        }
        .badge-gray {
            background: #f7fafc;
            color: #718096;
        }
        .link-primary {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .link-primary:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 0 20px;
            }
            .filters-grid {
                grid-template-columns: 1fr;
            }
            table {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('dashboard.index') }}" class="back-btn">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1>Manage Addresses</h1>
            </div>
            <div class="header-right">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <form action="{{ route('dashboard.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-content">
        <!-- Success Message -->
        @if(session('success'))
        <div class="success-msg">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="color: #43e97b;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
            </svg>
            <strong>{{ session('success') }}</strong>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Addresses</h3>
                <div class="number">{{ $addresses->total() }}</div>
                <div class="description">Customer locations</div>
            </div>

            <div class="stat-card">
                <h3>Cities</h3>
                <div class="number">{{ $cities->count() }}</div>
                <div class="description">Unique cities</div>
            </div>

            <div class="stat-card">
                <h3>Districts</h3>
                <div class="number">{{ $districts->count() }}</div>
                <div class="description">Unique districts</div>
            </div>

            <div class="stat-card">
                <h3>With Coordinates</h3>
                <div class="number">{{ \App\Models\Address::whereNotNull('latitude')->whereNotNull('longitude')->count() }}</div>
                <div class="description">GPS enabled</div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="filters-section">
            <form method="GET" action="{{ route('dashboard.addresses.index') }}">
                <div class="filters-grid">
                    <!-- Search -->
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search addresses..."
                               class="form-control">
                    </div>

                    <!-- City Filter -->
                    <div class="form-group">
                        <label>City</label>
                        <select name="city_id" class="form-control">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- District Filter -->
                    <div class="form-group">
                        <label>District</label>
                        <select name="district" class="form-control">
                            <option value="">All Districts</option>
                            @foreach($districts as $district)
                            <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="form-group">
                        <label>Sort By</label>
                        <select name="sort_by" class="form-control">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Newest</option>
                            <option value="city_id" {{ request('sort_by') == 'city_id' ? 'selected' : '' }}>City</option>
                            <option value="district" {{ request('sort_by') == 'district' ? 'selected' : '' }}>District</option>
                        </select>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-primary">Apply Filters</button>
                    <a href="{{ route('dashboard.addresses.index') }}" class="btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <!-- Addresses Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>City</th>
                        <th>District</th>
                        <th>Street</th>
                        <th>Building</th>
                        <th>Coordinates</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($addresses as $address)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    {{ substr($address->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $address->user->name }}</div>
                                    <div style="font-size: 0.85em; color: #718096;">{{ $address->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-blue">
                                {{ $address->city->getTranslation('name', app()->getLocale()) }}
                            </span>
                        </td>
                        <td>{{ $address->district }}</td>
                        <td>{{ $address->street }}</td>
                        <td>{{ $address->building_number }}</td>
                        <td>
                            @if($address->latitude && $address->longitude)
                            <span class="badge badge-green">✓</span>
                            @else
                            <span class="badge badge-gray">—</span>
                            @endif
                        </td>
                        <td style="color: #718096; font-size: 0.9em;">
                            {{ $address->created_at->diffForHumans() }}
                        </td>
                        <td>
                            <a href="{{ route('dashboard.addresses.show', $address->id) }}"
                               class="link-primary">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 60px 20px; color: #a0aec0;">
                            <svg style="width: 60px; height: 60px; margin-bottom: 15px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p>No addresses found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if($addresses->hasPages())
            <div style="padding: 20px; border-top: 1px solid #e2e8f0;">
                {{ $addresses->links() }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>
