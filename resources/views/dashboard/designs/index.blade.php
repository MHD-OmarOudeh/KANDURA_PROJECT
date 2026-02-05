<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kandura Designs - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8f9fa; }

        /* Header */
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 40px; box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3); }
        .header-content { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap; }
        .header-left { display: flex; align-items: center; gap: 15px; flex: 1; min-width: 200px; }
        .back-btn { padding: 8px 16px; background: rgba(255, 255, 255, 0.15); border: 2px solid rgba(255, 255, 255, 0.8); border-radius: 8px; color: white; text-decoration: none; font-weight: 600; transition: all 0.3s; white-space: nowrap; }
        .back-btn:hover { background: rgba(255, 255, 255, 0.3); }
        .header-left h1 { font-size: 1.5em; font-weight: 700; }
        .header-right { display: flex; gap: 20px; align-items: center; flex-wrap: wrap; }
        .user-name { font-weight: 600; }
        .btn-logout { padding: 8px 20px; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.8); border-radius: 8px; color: white; cursor: pointer; font-weight: 600; transition: all 0.3s; }
        .btn-logout:hover { background: white; color: #667eea; transform: translateY(-2px); }

        /* Main */
        .main-content { max-width: 1400px; margin: 30px auto; padding: 0 40px; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 5px solid #667eea; transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(102,126,234,0.15); }
        .stat-card:nth-child(2) { border-left-color: #f093fb; }
        .stat-card:nth-child(3) { border-left-color: #4facfe; }
        .stat-card:nth-child(4) { border-left-color: #43e97b; }
        .stat-card h3 { font-size: 0.85em; color: #718096; text-transform: uppercase; margin-bottom: 8px; }
        .stat-card .number { font-size: 2.5em; font-weight: 700; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        /* Section Title */
        .section-title { font-size: 1.6em; font-weight: 700; color: #2d3748; margin-bottom: 25px; display: flex; align-items: center; gap: 12px; }
        .section-title::before { content: ''; width: 5px; height: 35px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 3px; }

        /* Filters */
        .filter-section { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.9em; font-weight: 600; color: #2d3748; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 0.95em; transition: all 0.3s; }
        .form-control:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.1); }
        .btn-group { display: flex; gap: 15px; }
        .btn-primary { padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,0.3); }
        .btn-secondary { padding: 12px 30px; background: #e2e8f0; color: #2d3748; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; text-decoration: none; transition: all 0.3s; }
        .btn-secondary:hover { background: #cbd5e0; }

        /* Designs Grid */
        .designs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; }
        .design-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: all 0.3s; position: relative; }
        .design-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); transform: scaleX(0); transition: transform 0.3s; }
        .design-card:hover { transform: translateY(-8px); box-shadow: 0 15px 40px rgba(102,126,234,0.2); }
        .design-card:hover::before { transform: scaleX(1); }
        .design-image { height: 240px; background: #f7fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .design-image img { width: 100%; height: 100%; object-fit: cover; }
        .design-image-placeholder { font-size: 4em; color: #cbd5e0; }
        .design-content { padding: 25px; }
        .design-title { font-size: 1.2em; font-weight: 700; color: #2d3748; margin-bottom: 10px; }
        .design-desc { font-size: 0.9em; color: #718096; margin-bottom: 15px; line-height: 1.5; }
        .design-user { display: flex; align-items: center; gap: 8px; font-size: 0.85em; color: #a0aec0; margin-bottom: 15px; }
        .design-footer { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .design-price { font-size: 2em; font-weight: 700; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .design-sizes { display: flex; gap: 5px; flex-wrap: wrap; }
        .size-badge { padding: 4px 10px; background: #edf2f7; border-radius: 6px; font-size: 0.75em; font-weight: 600; color: #2d3748; }
        .btn-view { display: block; width: 100%; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; text-decoration: none; border-radius: 10px; font-weight: 600; transition: all 0.3s; }
        .btn-view:hover { box-shadow: 0 8px 20px rgba(102,126,234,0.3); }

        /* Empty State */
        .empty-state { grid-column: 1/-1; text-align: center; padding: 60px; background: white; border-radius: 15px; }
        .empty-icon { font-size: 5em; margin-bottom: 15px; opacity: 0.3; }
        .empty-text { font-size: 1.2em; color: #a0aec0; }

        @media (max-width: 768px) {
            .main-content { padding: 0 20px; }
            .designs-grid { grid-template-columns: 1fr; }
        }

        .language-dropdown {
            position: relative;
        }

        .language-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .language-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .language-menu {
            position: absolute;
            top: 110%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            min-width: 180px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .language-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .language-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #2d3748;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .language-menu a:hover {
            background: #f7fafc;
        }

        .language-menu a.active {
            background: #eef2ff;
            color: #667eea;
            font-weight: 600;
        }
    </style>
</head>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>
<script src="{{ asset('js/firebase-init.js') }}"></script>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('dashboard.index') }}" class="back-btn">←</a>
                <h1>{{ __('dashboard.kandura_designs') }}</h1>
            </div>
            <div class="header-right">
                <div class="language-dropdown">
                    <button class="language-btn" onclick="toggleLanguage()">
                        <span>🌐</span>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <span>▼</span>
                    </button>
                    <div class="language-menu" id="languageMenu">
                        <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
                            <span>🇬🇧</span>
                            <span>English</span>
                        </a>
                        <a href="{{ route('language.switch', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                            <span>🇸🇦</span>
                            <span>العربية</span>
                        </a>
                    </div>
                </div>
                <span class="user-name">{{ auth()->user()->name }}</span>
                <form action="{{ route('dashboard.logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Designs</h3>
                <div class="number">{{ $designs->total() }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number">{{ \App\Models\Design::distinct('user_id')->count('user_id') }}</div>
            </div>
            <div class="stat-card">
                <h3>Average Price</h3>
                <div class="number">${{ number_format(\App\Models\Design::avg('price'), 0) }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Images</h3>
                <div class="number">{{ \App\Models\DesignImage::count() }}</div>
            </div>
        </div>

        <!-- Filters -->
        <h2 class="section-title">Filter Designs</h2>
        <div class="filter-section">
            <form method="GET">
                <div class="filter-grid">
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Design or user...">
                    </div>
                    <div class="form-group">
                        <label>Size</label>
                        <select name="measurement_id" class="form-control">
                            <option value="">All Sizes</option>
                            @foreach($measurements as $m)
                            <option value="{{ $m->id }}" {{ request('measurement_id') == $m->id ? 'selected' : '' }}>{{ $m->size }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Min Price</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="0.00" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Max Price</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="1000.00" step="0.01">
                    </div>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn-primary">Apply Filters</button>
                    <a href="{{ route('dashboard.designs.index') }}" class="btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <!-- Designs Grid -->
        <h2 class="section-title">All Designs</h2>
        <div class="designs-grid">
            @forelse($designs as $design)
            <div class="design-card">
                <div class="design-image">
                    @if($design->images->first())
                    <img src="{{ asset('storage/' . $design->images->first()->image_path) }}" alt="{{ $design->getTranslation('name', 'en') }}">
                    @else
                    <div class="design-image-placeholder">👔</div>
                    @endif
                </div>
                <div class="design-content">
                    <h3 class="design-title">{{ $design->getTranslation('name', 'en') }}</h3>
                    <p class="design-desc">{{ Str::limit($design->getTranslation('description', 'en'), 80) }}</p>
                    <div class="design-user">
                        <span>👤</span>
                        <span>{{ $design->user->name }}</span>
                    </div>
                    <div class="design-footer">
                        <div class="design-price">${{ number_format($design->price, 2) }}</div>
                        <div class="design-sizes">
                            @foreach($design->measurements->take(3) as $m)
                            <span class="size-badge">{{ $m->size }}</span>
                            @endforeach
                            @if($design->measurements->count() > 3)
                            <span class="size-badge">+{{ $design->measurements->count() - 3 }}</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('dashboard.designs.show', $design) }}" class="btn-view">View Details</a>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p class="empty-text">No designs found</p>
            </div>
            @endforelse
        </div>

        @if($designs->hasPages())
        <div style="margin-top: 30px;">{{ $designs->links() }}</div>
        @endif
    </div>

    <script>
        function toggleLanguage() {
            const menu = document.getElementById('languageMenu');
            menu.classList.toggle('active');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.language-dropdown');
            const menu = document.getElementById('languageMenu');
            if (dropdown && !dropdown.contains(event.target)) {
                menu.classList.remove('active');
            }
        });
    </script>
</body>
</html>
