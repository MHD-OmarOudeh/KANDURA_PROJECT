<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Design Option</title>
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
        .form-group {
            margin-bottom: 25px;
        }
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
    <div class="header">
        <div class="header-content">
            <a href="/dashboard/design-options" class="back-btn">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1>Create Design Option</h1>
        </div>
    </div>

    <div class="main-content">
        <!-- Error Alert -->
        <div class="alert" style="display: none;">
            <strong>⚠️ Error:</strong> Please check the form fields.
        </div>

        <div class="card">
            <form action="{{ route('design-options.create', $designOption) }} " method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="name_en">Name (English) *</label>
                    <input type="text" id="name_en" name="name[en]" class="form-control" placeholder="e.g., White, Cotton" required>
                </div>

                <div class="form-group">
                    <label for="name_ar">Name (Arabic) *</label>
                    <input type="text" id="name_ar" name="name[ar]" class="form-control" placeholder="مثال: أبيض، قطن" dir="rtl" required>
                </div>

                <div class="form-group">
                    <label for="type">Type *</label>
                    <select id="type" name="type" class="form-control" required onchange="toggleColorCode()">
                        <option value="">Select Type</option>
                        <option value="color">Color</option>
                        <option value="dome_type">Dome Type</option>
                        <option value="fabric_type">Fabric Type</option>
                        <option value="sleeve_style">Sleeve Style</option>
                    </select>
                </div>

                <div class="form-group" id="colorCodeField" style="display: none;">
                    <label for="color_code">Color Code *</label>
                    <div class="color-picker-group">
                        <input type="text" id="color_code" name="color_code" class="form-control" placeholder="#FFFFFF">
                        <input type="color" id="colorPicker" value="#FFFFFF" onchange="syncColor()">
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Image (Optional)</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <p class="helper-text">Max: 2MB. Formats: JPEG, PNG, JPG, WEBP</p>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Create Option</button>
                    <a href="/dashboard/design-options" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleColorCode() {
            const type = document.getElementById('type').value;
            const field = document.getElementById('colorCodeField');
            const input = document.getElementById('color_code');
            if (type === 'color') {
                field.style.display = 'block';
                input.required = true;
            } else {
                field.style.display = 'none';
                input.required = false;
            }
        }

        function syncColor() {
            const picker = document.getElementById('colorPicker');
            const input = document.getElementById('color_code');
            input.value = picker.value;
        }

        document.getElementById('color_code').addEventListener('input', function() {
            document.getElementById('colorPicker').value = this.value;
        });
    </script>
</body>
</html>
