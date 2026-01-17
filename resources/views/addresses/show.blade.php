<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Details - Kandura Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8f9fa; }

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
        .header-left { display: flex; align-items: center; gap: 15px; }
        .back-btn { color: white; transition: transform 0.3s; text-decoration: none; }
        .back-btn:hover { transform: translateX(-5px); }
        .header-left h1 { font-size: 1.5em; font-weight: 700; }
        .header-right { display: flex; gap: 20px; align-items: center; }
        .btn-logout {
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
        }

        .main-content { max-width: 1400px; margin: 30px auto; padding: 0 40px; }
        .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 25px;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            font-size: 1.3em;
            font-weight: 700;
        }
        .card-body { padding: 30px; }

        .user-info { display: flex; align-items: center; gap: 20px; margin-bottom: 25px; }
        .user-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2em;
            font-weight: 700;
        }

        .info-row { padding: 15px 0; border-bottom: 1px solid #e2e8f0; }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            font-size: 0.85em;
            color: #718096;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value { font-size: 1.1em; color: #2d3748; font-weight: 600; }

        .badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            background: #eef2ff;
            color: #667eea;
        }

        #map { height: 400px; border-radius: 10px; }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .btn-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #2d3748;
        }
        .btn-secondary:hover { background: #cbd5e0; }

        .btn-group { display: flex; gap: 15px; }

        .status-badge {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .status-yes { background: #e6fffa; color: #43e97b; }
        .status-no { background: #fff5f5; color: #f5576c; }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .modal-header h3 {
            font-size: 1.5em;
            color: #2d3748;
        }
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5em;
            cursor: pointer;
            color: #718096;
        }
        .close-btn:hover { color: #2d3748; }

        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        @media (max-width: 1024px) {
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('dashboard.addresses.index') }}" class="back-btn">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1>Address Details</h1>
            </div>
            <div class="header-right">
                <span style="font-weight: 600;">{{ auth()->user()->name }}</span>
                <form action="{{ route('dashboard.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="grid-2">
            <!-- Left Column -->
            <div>
                <!-- User Card -->
                <div class="card">
                    <div class="card-header">👤 User Information</div>
                    <div class="card-body">
                        <div class="user-info">
                            <div class="user-avatar">{{ substr($address->user->name, 0, 1) }}</div>
                            <div>
                                <h3 style="font-size: 1.3em; margin-bottom: 5px;">{{ $address->user->name }}</h3>
                                <p style="color: #718096;">{{ $address->user->email }}</p>
                                @if($address->user->phone)
                                <p style="color: #718096; font-size: 0.9em; margin-top: 5px;">📞 {{ $address->user->phone }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="btn-group">
                            <a href="mailto:{{ $address->user->email }}" class="btn btn-primary" style="flex: 1;">Send Email</a>
                            <button class="btn btn-secondary" style="flex: 1;">View Profile</button>
                        </div>
                    </div>
                </div>

                <!-- Address Details -->
                <div class="card">
                    <div class="card-header">📍 Address Details</div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">City</div>
                            <div class="info-value">
                                <span class="badge">{{ $address->city->getTranslation('name', app()->getLocale()) }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">District</div>
                            <div class="info-value">{{ $address->district }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Street</div>
                            <div class="info-value">{{ $address->street }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Building Number</div>
                            <div class="info-value">{{ $address->building_number }}</div>
                        </div>
                        @if($address->additional_info)
                        <div class="info-row">
                            <div class="info-label">Additional Information</div>
                            <div class="info-value" style="font-size: 0.95em; line-height: 1.6;">{{ $address->additional_info }}</div>
                        </div>
                        @endif
                        @if($address->latitude && $address->longitude)
                        <div class="info-row">
                            <div class="info-label">Coordinates</div>
                            <div class="info-value" style="font-family: monospace; font-size: 0.95em;">
                                {{ $address->latitude }}, {{ $address->longitude }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Map -->
                @if($address->latitude && $address->longitude)
                <div class="card">
                    <div class="card-header">🗺️ Location on Map</div>
                    <div class="card-body">
                        <div id="map"></div>
                        <div class="btn-group" style="margin-top: 20px;">
                            <a href="https://www.google.com/maps?q={{ $address->latitude }},{{ $address->longitude }}"
                               target="_blank" class="btn btn-primary" style="flex: 1;">Open in Google Maps</a>
                            <button onclick="copyCoordinates()" class="btn btn-secondary" style="flex: 1;">Copy Coordinates</button>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div>
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">⚡ Quick Actions</div>
                    <div class="card-body">
                        <div class="btn-group" style="flex-direction: column;">
                            <button onclick="openEditModal()" class="btn btn-primary" style="width: 100%;">✏️ Edit Address</button>
                            <button onclick="openDeleteModal()" class="btn btn-danger" style="width: 100%;">🗑️ Delete Address</button>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="card">
                    <div class="card-header">📊 Metadata</div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">Address ID</div>
                            <div class="info-value" style="font-family: monospace;">#{{ $address->id }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">User ID</div>
                            <div class="info-value" style="font-family: monospace;">#{{ $address->user_id }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">City ID</div>
                            <div class="info-value" style="font-family: monospace;">#{{ $address->city_id }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Created At</div>
                            <div class="info-value">{{ $address->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.85em; color: #a0aec0; margin-top: 5px;">{{ $address->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Last Updated</div>
                            <div class="info-value">{{ $address->updated_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.85em; color: #a0aec0; margin-top: 5px;">{{ $address->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card">
                    <div class="card-header">Status</div>
                    <div class="card-body">
                        <div class="status-badge {{ $address->latitude && $address->longitude ? 'status-yes' : 'status-no' }}">
                            <span style="font-weight: 600;">Coordinates Available</span>
                            <span style="font-weight: 700; font-size: 1.1em;">{{ $address->latitude && $address->longitude ? 'YES' : 'NO' }}</span>
                        </div>
                        <div class="status-badge {{ $address->additional_info ? 'status-yes' : 'status-no' }}">
                            <span style="font-weight: 600;">Additional Info</span>
                            <span style="font-weight: 700; font-size: 1.1em;">{{ $address->additional_info ? 'YES' : 'NO' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Address</h3>
                <button class="close-btn" onclick="closeEditModal()">×</button>
            </div>
            <form action="{{ route('dashboard.addresses.update', $address->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>City *</label>
                    <select name="city_id" class="form-control" required>
                        @foreach(\App\Models\City::all() as $city)
                        <option value="{{ $city->id }}" {{ $address->city_id == $city->id ? 'selected' : '' }}>
                            {{ $city->getTranslation('name', app()->getLocale()) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>District *</label>
                    <input type="text" name="district" value="{{ $address->district }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Street *</label>
                    <input type="text" name="street" value="{{ $address->street }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Building Number *</label>
                    <input type="text" name="building_number" value="{{ $address->building_number }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Latitude</label>
                    <input type="text" name="latitude" value="{{ $address->latitude }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>Longitude</label>
                    <input type="text" name="longitude" value="{{ $address->longitude }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>Additional Information</label>
                    <textarea name="additional_info" class="form-control" rows="3">{{ $address->additional_info }}</textarea>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Update Address</button>
                    <button type="button" onclick="closeEditModal()" class="btn btn-secondary" style="flex: 1;">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Delete Address</h3>
                <button class="close-btn" onclick="closeDeleteModal()">×</button>
            </div>
            <p style="color: #718096; margin-bottom: 25px;">Are you sure you want to delete this address? This action cannot be undone.</p>
            <form action="{{ route('dashboard.addresses.destroy', $address->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="btn-group">
                    <button type="submit" class="btn btn-danger" style="flex: 1;">Delete</button>
                    <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary" style="flex: 1;">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @if($address->latitude && $address->longitude)
    <script>
        const map = L.map('map').setView([{{ $address->latitude }}, {{ $address->longitude }}], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        const marker = L.marker([{{ $address->latitude }}, {{ $address->longitude }}]).addTo(map);
        marker.bindPopup("<b>{{ $address->user->name }}</b><br>{{ $address->city->getTranslation('name', app()->getLocale()) }}, {{ $address->district }}").openPopup();

        function copyCoordinates() {
            const coords = "{{ $address->latitude }}, {{ $address->longitude }}";
            navigator.clipboard.writeText(coords).then(() => {
                alert('Coordinates copied to clipboard!');
            });
        }
    </script>
    @endif

    <script>
        function openEditModal() {
            document.getElementById('editModal').classList.add('active');
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.add('active');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            if (event.target == editModal) closeEditModal();
            if (event.target == deleteModal) closeDeleteModal();
        }
    </script>
</body>
</html>
