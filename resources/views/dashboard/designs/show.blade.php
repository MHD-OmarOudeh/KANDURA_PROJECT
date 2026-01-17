<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Design Details - Kandura Store</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Purple Theme -->
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #764ba2;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .purple-bg {
            background: var(--primary-gradient) !important;
        }

        .purple-text {
            color: var(--primary) !important;
        }

        .purple-gradient-text {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .tab-active {
            border-bottom-color: var(--primary) !important;
            color: var(--primary) !important;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Header -->
    <nav class="purple-bg shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 text-white">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard.designs.index') }}" class="hover:opacity-80">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold">Design Details</h1>
                </div>

                <div class="flex items-center gap-4">
                    <span>{{ auth()->user()->name }}</span>
                    <form action="{{ route('dashboard.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-white font-medium hover:underline">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- User Card -->
        <div class="purple-bg rounded-lg shadow-lg p-8 mb-6 text-white">
            <div class="flex items-center gap-6">
                <div class="h-20 w-20 rounded-full bg-white/20 flex items-center justify-center text-3xl font-bold border-4 border-white/30">
                    {{ substr($design->user->name, 0, 1) }}
                </div>

                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-2">{{ $design->user->name }}</h2>
                    <div class="flex flex-wrap gap-4 text-sm opacity-90">
                        <div class="flex items-center gap-2">
                            📧 {{ $design->user->email }}
                        </div>

                        @if($design->user->phone)
                        <div class="flex items-center gap-2">
                            📞 {{ $design->user->phone }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Images -->
            <div class="space-y-6">

                @if($design->images->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="relative h-96 bg-gray-200">
                        <img id="mainImage"
                             src="{{ asset('storage/' . $design->images->first()->image_path) }}"
                             class="w-full h-full object-cover">
                    </div>
                </div>

                @if($design->images->count() > 1)
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($design->images as $image)
                        <div class="cursor-pointer hover:opacity-75 transition"
                             onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $image->image_path) }}'">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                 class="w-full h-24 object-cover rounded border-2
                                 {{ $loop->first ? 'border-[--primary]' : 'border-gray-200' }}">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @else
                <div class="bg-white rounded-lg shadow-sm h-96 flex items-center justify-center text-gray-400 text-4xl">
                    📷
                </div>
                @endif
            </div>


            <!-- Details -->
            <div class="space-y-6">

                <!-- Basic Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ $design->getTranslation('name', 'en') }}
                    </h3>

                    <div class="flex items-center justify-between mb-6 pb-6 border-b">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Price</p>
                            <p class="text-4xl font-extrabold purple-gradient-text">
                                ${{ number_format($design->price, 2) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1">Design ID</p>
                            <p class="text-lg font-semibold text-gray-700">#{{ $design->id }}</p>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="border-b mb-4">
                        <nav class="flex gap-4">
                            <button onclick="showTab('english')" id="tab-english"
                                class="tab-button tab-active px-4 py-2 border-b-2 font-medium">
                                English
                            </button>
                            <button onclick="showTab('arabic')" id="tab-arabic"
                                class="tab-button px-4 py-2 border-b-2 border-transparent text-gray-500 hover:text-[--primary]">
                                عربي
                            </button>
                        </nav>
                    </div>

                    <!-- English -->
                    <div id="content-english">
                        <h4 class="font-semibold text-gray-900 mb-2">Name</h4>
                        <p class="text-gray-700 mb-4">{{ $design->getTranslation('name', 'en') }}</p>

                        <h4 class="font-semibold text-gray-900 mb-2">Description</h4>
                        <p class="text-gray-700">{{ $design->getTranslation('description', 'en') }}</p>
                    </div>

                    <!-- Arabic -->
                    <div id="content-arabic" class="hidden" dir="rtl">
                        <h4 class="font-semibold text-gray-900 mb-2">الاسم</h4>
                        <p class="text-gray-700 mb-4">{{ $design->getTranslation('name', 'ar') }}</p>

                        <h4 class="font-semibold text-gray-900 mb-2">الوصف</h4>
                        <p class="text-gray-700">{{ $design->getTranslation('description', 'ar') }}</p>
                    </div>
                </div>

                <!-- Sizes -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Available Sizes</h4>
                    <div class="flex flex-wrap gap-3">
                        @forelse($design->measurements as $measurement)
                        <div class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg font-medium">
                            {{ $measurement->size }}
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">No sizes specified</p>
                        @endforelse
                    </div>
                </div>

                <!-- Options -->
                @if($design->designOptions->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Design Options</h4>

                    @php $groupedOptions = $design->designOptions->groupBy('type'); @endphp

                    @foreach($groupedOptions as $type => $options)
                    <div class="mb-4 pb-4 border-b last:border-0">
                        <p class="text-sm text-gray-500 mb-2">{{ ucwords(str_replace('_',' ',$type)) }}</p>

                        <div class="flex flex-wrap gap-2">
                            @foreach($options as $option)
                            <div class="flex items-center gap-2 px-3 py-2 bg-purple-50 rounded-lg border-l-4 border-[--primary]">
                                @if($option->type === 'color' && $option->color_code)
                                <div class="w-6 h-6 rounded-full border"
                                     style="background-color: {{ $option->color_code }}"></div>
                                @endif
                                <span class="text-sm font-medium">{{ $option->getTranslation('name', 'en') }}</span>
                            </div>
                            @endforeach
                        </div>

                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Metadata -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Information</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Created</span>
                            <span>{{ $design->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Last Updated</span>
                            <span>{{ $design->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Images</span>
                            <span>{{ $design->images->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <a href="{{ route('dashboard.designs.index') }}"
                       class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 rounded-lg text-center font-medium">
                        Back to List
                    </a>
                </div>

            </div>
        </div>
    </div>

<script>
function showTab(tab) {
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('tab-active');
    });

    document.querySelectorAll('[id^="content-"]').forEach(content => {
        content.classList.add('hidden');
    });

    document.getElementById('tab-' + tab).classList.add('tab-active');
    document.getElementById('content-' + tab).classList.remove('hidden');
}
</script>

</body>
</html>
