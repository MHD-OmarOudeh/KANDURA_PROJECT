<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Design Options - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8f9fa; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 40px; box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3); }
        .header-content { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .header-left { display: flex; align-items: center; gap: 15px; }
        .back-btn { color: white; text-decoration: none; font-size: 1.5em; transition: transform 0.3s; }
        .back-btn:hover { transform: translateX(-5px); }
        .header-left h1 { font-size: 1.5em; font-weight: 700; }
        .header-right { display: flex; gap: 15px; align-items: center; }
        .btn-logout { padding: 8px 20px; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.8); border-radius: 8px; color: white; cursor: pointer; font-weight: 600; transition: all 0.3s; }
        .btn-logout:hover { background: white; color: #667eea; }
        .main-content { max-width: 1400px; margin: 30px auto; padding: 0 40px; }
        .alert { padding: 20px; border-radius: 10px; margin-bottom: 20px; border-left: 5px solid; }
        .alert-success { background: #f0fdf4; border-color: #22c55e; color: #166534; }
        .alert-error { background: #fef2f2; border-color: #ef4444; color: #991b1b; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 5px solid #667eea; transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card:nth-child(2) { border-left-color: #f093fb; }
        .stat-card:nth-child(3) { border-left-color: #4facfe; }
        .stat-card:nth-child(4) { border-left-color: #43e97b; }
        .stat-card h3 { font-size: 0.85em; color: #718096; text-transform: uppercase; margin-bottom: 8px; }
        .stat-card .number { font-size: 2.5em; font-weight: 700; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .section-title { font-size: 1.6em; font-weight: 700; color: #2d3748; display: flex; align-items: center; gap: 12px; }
        .section-title::before { content: ''; width: 5px; height: 35px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 3px; }
        .btn-add { padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.3s; display: inline-block; }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,0.3); }
        .filter-section { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.9em; font-weight: 600; color: #2d3748; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 0.95em; transition: all 0.3s; }
        .form-control:focus { outline: none; border-color: #667eea; }
        .btn-group { display: flex; gap: 15px; }
        .btn-primary { padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-secondary { padding: 12px 30px; background: #e2e8f0; color: #2d3748; border: none; border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-block; }
        .table-section { background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f7fafc; }
        th { padding: 18px 20px; text-align: left; font-size: 0.85em; color: #718096; text-transform: uppercase; font-weight: 600; }
        td { padding: 18px 20px; border-bottom: 1px solid #e2e8f0; }
        tr:hover { background: #f7fafc; }
        .type-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8em; font-weight: 600; display: inline-block; }
        .badge-color { background: #fee2e2; color: #991b1b; }
        .badge-dome { background: #dbeafe; color: #1e40af; }
        .badge-fabric { background: #dcfce7; color: #166534; }
        .badge-sleeve { background: #e9d5ff; color: #6b21a8; }
        .color-preview { display: flex; align-items: center; gap: 10px; }
        .color-box { width: 35px; height: 35px; border-radius: 8px; border: 2px solid #e2e8f0; }
        .color-code { font-size: 0.85em; color: #718096; }
        .preview-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .action-links { display: flex; gap: 15px; }
        .link-edit { color: #667eea; font-weight: 600; text-decoration: none; }
        .link-delete { color: #ef4444; font-weight: 600; background: none; border: none; cursor: pointer; }
        .empty-state { text-align: center; padding: 60px; }
        .empty-icon { font-size: 5em; opacity: 0.3; }
        @media (max-width: 768px) { .main-content { padding: 0 20px; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('dashboard.index') }}" class="back-btn">←</a>
                <h1>Design Options</h1>
            </div>
            <div class="header-right">
                <span>{{ auth()->user()->name }}</span>
                <form action="{{ route('dashboard.logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Options</h3>
                <div class="number">{{ $options->total() }}</div>
            </div>
            <div class="stat-card">
                <h3>Colors</h3>
                <div class="number">{{ \App\Models\DesignOption::where('type', 'color')->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>Fabrics</h3>
                <div class="number">{{ \App\Models\DesignOption::where('type', 'fabric_type')->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>Sleeve Types</h3>
                <div class="number">{{ \App\Models\DesignOption::where('type', 'sleeve_type')->count() }}</div>
            </div>
        </div>

        <div class="section-header">
            <h2 class="section-title">Manage Options</h2>
            <a href="{{ route('dashboard.design-options.create') }}" class="btn-add">+ Add New Option</a>
        </div>

        <div class="filter-section">
            <form method="GET">
                <div class="filter-grid">
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search...">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option value="">All Types</option>
                            @foreach($types as $key => $value)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Per Page</label>
                        <select name="per_page" class="form-control">
                            <option value="15">15</option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn-primary">Apply Filters</button>
                    <a href="{{ route('dashboard.design-options.index') }}" class="btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Name (EN)</th>
                        <th>Name (AR)</th>
                        <th>Type</th>
                        <th>Preview</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($options as $option)
                    <tr>
                        <td><strong>{{ $option->getTranslation('name', 'en') }}</strong></td>
                        <td>{{ $option->getTranslation('name', 'ar') }}</td>
                        <td>
                            <span class="type-badge badge-{{ $option->type === 'color' ? 'color' : ($option->type === 'dome_type' ? 'dome' : ($option->type === 'fabric_type' ? 'fabric' : 'sleeve')) }}">
                                {{ $option->type_name }}
                            </span>
                        </td>
                        <td>
                            @if($option->type === 'color' && $option->color_code)
                            <div class="color-preview">
                                <div class="color-box" style="background-color: {{ $option->color_code }}"></div>
                                <span class="color-code">{{ $option->color_code }}</span>
                            </div>
                            @elseif($option->image)
                            <img src="{{ asset('storage/' . $option->image) }}" class="preview-img">
                            @else
                            <span style="color: #a0aec0;">No preview</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="{{ route('dashboard.design-options.edit', $option) }}" class="link-edit">Edit</a>
                                <form action="{{ route('dashboard.design-options.destroy', $option) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">📭</div>
                                <p>No options found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($options->hasPages())
            <div style="padding: 20px;">{{ $options->links() }}</div>
            @endif
        </div>
    </div>
</body>
</html>
