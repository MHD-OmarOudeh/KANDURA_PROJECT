<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Design Option</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .back-btn { color: white; transition: transform 0.3s; }
        .back-btn:hover { transform: translateX(-5px); }
        .header h1 { font-size: 1.5em; font-weight: 700; }
        .main-content { max-width: 1000px; margin: 30px auto; padding: 0 40px; }
        .alert {
            background: white;
            border-left: 5px solid #f5576c;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.2);
        }
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 40px;
        }
        .form-group { margin-bottom: 25px; }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 0.95em;
        }
        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        .color-picker-group {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .color-picker-group input[type="text"] { flex: 1; }
        .color-picker-group input[type="color"] {
            width: 60px;
            height: 50px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 35px;
        }
        .btn {
            flex: 1;
            padding: 16px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.05em;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
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
        .btn-secondary {
            background: #e2e8f0;
            color: #2d3748;
        }
        .btn-secondary:hover { background: #cbd5e0; }
        .helper-text {
            font-size: 0.85em;
            color: #718096;
            margin-top: 5px;
        }
        select.form-control { cursor: pointer; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <a href="{{ route('dashboard.design-options.index') }}" class="back-btn">
                &#8592;
            </a>
            <h1>Edit Design Option</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if($errors->any())
            <div class="alert">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="card">
            <form action="{{ route('dashboard.design-options.update', $designOption) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Name (English) -->
                <div class="form-group">
                    <label>Name (English) *</label>
                    <input type="text" name="name[en]" value="{{ old('name.en', $designOption->getTranslation('name', 'en')) }}" required class="form-control">
                </div>

                <!-- Name (Arabic) -->
                <div class="form-group">
                    <label>Name (Arabic) *</label>
                    <input type="text" name="name[ar]" value="{{ old('name.ar', $designOption->getTranslation('name', 'ar')) }}" required dir="rtl" class="form-control">
                </div>

                <!-- Type -->
                <div class="form-group">
                    <label>Type *</label>
                    <select name="type" id="type" required class="form-control" onchange="toggleColorCode()">
                        @foreach($types as $key => $value)
                            <option value="{{ $key }}" {{ $designOption->type == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Color Code -->
                <div id="colorCodeField" style="display: {{ $designOption->type === 'color' ? 'block' : 'none' }};" class="form-group">
                    <label>Color Code *</label>
                    <div class="color-picker-group">
                        <input type="text" name="color_code" value="{{ old('color_code', $designOption->color_code) }}" class="form-control">
                        <input type="color" id="colorPicker" value="{{ $designOption->color_code ?? '#FFFFFF' }}" onchange="document.querySelector('input[name=color_code]').value = this.value">
                    </div>
                </div>

                <!-- Current Image -->
                @if($designOption->image)
                <div class="form-group">
                    <label>Current Image</label>
                    <img src="{{ asset('storage/' . $designOption->image) }}" class="w-32 h-32 object-cover rounded border">
                </div>
                @endif

                <!-- New Image -->
                <div class="form-group">
                    <label>Change Image</label>
                    <input type="file" name="image" accept="image/*" class="form-control">
                    <p class="helper-text">Upload a new image to replace the current one.</p>
                </div>

                <!-- Buttons -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Update Option</button>
                    <a href="{{ route('dashboard.design-options.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleColorCode() {
            const type = document.getElementById('type').value;
            const field = document.getElementById('colorCodeField');
            if (type === 'color') {
                field.style.display = 'block';
            } else {
                field.style.display = 'none';
            }
        }

        document.querySelector('input[name=color_code]').addEventListener('input', function() {
            document.getElementById('colorPicker').value = this.value;
        });
    </script>
</body>
</html>
