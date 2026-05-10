<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ $user->name }} - Lumina Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root {
            --sidebar-width: 280px;
        }

        body {
            background: #0a0a0c;
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(168, 85, 247, 0.05) 0%, transparent 50%);
        }

        .dashboard-layout {
            max-width: 1400px;
            margin: 120px auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: var(--sidebar-width) 1fr;
            gap: 3rem;
        }

        .dashboard-sidebar {
            position: sticky;
            top: 120px;
            height: fit-content;
        }

        .sidebar-card {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: 2.5rem;
            padding: 2.5rem;
            backdrop-filter: blur(20px);
            animation: slideInLeft 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }

        .avatar-large {
            width: 100px;
            height: 100px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 900;
            margin: 0 auto 1.5rem;
            color: white;
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.5);
            border: 4px solid rgba(255,255,255,0.1);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1.125rem 1.5rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 1.25rem;
            transition: all 0.3s;
            font-weight: 600;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.03);
            color: white;
            transform: translateX(8px);
        }

        .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.3);
        }

        .content-card {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: 2.5rem;
            padding: 4rem;
            backdrop-filter: blur(20px);
            min-height: 700px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .stat-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            padding: 2rem;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.04);
            border-color: var(--primary);
        }

        .tab-title {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 2.5rem;
            letter-spacing: -0.03em;
        }

        .tab-title span {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-control {
            width: 100%;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 1.25rem;
            padding: 1.25rem;
            color: white;
            font-family: inherit;
            transition: all 0.3s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: rgba(255,255,255,0.06);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        @media (max-width: 1100px) {
            .dashboard-layout { grid-template-columns: 1fr; }
            .dashboard-sidebar { position: relative; top: 0; margin-bottom: 2rem; }
            .content-card { padding: 2.5rem; }
        }

        /* Collection Card */
        .collection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .collection-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            padding: 2rem;
            transition: 0.3s;
            cursor: pointer;
        }

        .collection-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            background: rgba(255,255,255,0.04);
        }

        .collection-preview {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            height: 120px;
            margin-bottom: 1.5rem;
        }

        .preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.75rem;
            background: rgba(255,255,255,0.05);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(10px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .modal-content {
            background: #111114;
            border: 1px solid var(--glass-border);
            border-radius: 2.5rem;
            width: 100%;
            max-width: 500px;
            padding: 3rem;
            position: relative;
            animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes modalPop { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
    </style>
</head>
<body x-data="{ openCollectionModal: false }">
    @include('layouts.navbar')

    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-card">
                <div style="text-align: center; margin-bottom: 3rem;">
                    <div class="avatar-large">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem; font-weight: 800;">{{ $user->name }}</h2>
                    <span class="subscription-badge status-{{ $user->subscription_type?->value ?? 'free' }}">
                        {{ $user->subscription_type?->label() ?? 'Free User' }}
                    </span>
                </div>

                <nav>
                    <a href="{{ route('dashboard', ['tab' => 'overview']) }}" class="nav-link {{ $tab == 'overview' ? 'active' : '' }}">
                        <i class="fa-solid fa-house-chimney"></i> Overview
                    </a>
                    <a href="{{ route('dashboard', ['tab' => 'downloads']) }}" class="nav-link {{ $tab == 'downloads' ? 'active' : '' }}">
                        <i class="fa-solid fa-cloud-arrow-down"></i> Downloads
                    </a>
                    <a href="{{ route('dashboard', ['tab' => 'collections']) }}" class="nav-link {{ $tab == 'collections' ? 'active' : '' }}">
                        <i class="fa-solid fa-folder-open"></i> Collections
                    </a>
                    <a href="{{ route('dashboard', ['tab' => 'wishlist']) }}" class="nav-link {{ $tab == 'wishlist' ? 'active' : '' }}">
                        <i class="fa-solid fa-heart"></i> Wishlist
                    </a>
                    <a href="{{ route('dashboard', ['tab' => 'reviews']) }}" class="nav-link {{ $tab == 'reviews' ? 'active' : '' }}">
                        <i class="fa-solid fa-star-half-stroke"></i> My Reviews
                    </a>
                    <a href="{{ route('dashboard', ['tab' => 'settings']) }}" class="nav-link {{ $tab == 'settings' ? 'active' : '' }}">
                        <i class="fa-solid fa-sliders"></i> Settings
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="content-card">
            @if($tab == 'overview')
                <h1 class="tab-title">Welcome, <span>{{ explode(' ', $user->name)[0] }}!</span></h1>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 4rem;">
                    <div class="stat-card">
                        <div style="color: var(--text-muted); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">Total Downloads</div>
                        <div style="font-size: 3rem; font-weight: 900; color: var(--primary);">{{ $user->downloadLogs()->count() }}</div>
                    </div>
                    <div class="stat-card">
                        <div style="color: var(--text-muted); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">Collections</div>
                        <div style="font-size: 3rem; font-weight: 900; color: var(--accent);">{{ $user->collections()->count() }}</div>
                    </div>
                    <div class="stat-card">
                        <div style="color: var(--text-muted); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">Account Type</div>
                        <div style="font-size: 1.75rem; font-weight: 900; color: var(--primary); margin-top: 1rem;">{{ $user->subscription_type?->label() ?? 'Free' }}</div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 3rem;">
                    <div>
                        <h3 style="margin-bottom: 2rem; font-weight: 800;">Recent Downloads</h3>
                        @forelse($recentDownloads as $log)
                            <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 1.5rem; padding: 1.5rem; margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between; transition: 0.3s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                                <div style="display: flex; align-items: center; gap: 1.5rem;">
                                    <img src="{{ $log->product->getPreviewUrl() }}" style="width: 60px; height: 60px; border-radius: 1rem; object-fit: cover; border: 1px solid var(--glass-border);">
                                    <div>
                                        <div style="font-weight: 800; font-size: 1.1rem; margin-bottom: 0.25rem;">{{ $log->product->title }}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $log->downloaded_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('products.download', $log->product) }}" class="btn" style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 3rem; background: rgba(255,255,255,0.01); border: 1px dashed var(--glass-border); border-radius: 1.5rem;">
                                <p style="color: var(--text-muted);">No downloads yet.</p>
                            </div>
                        @endforelse
                    </div>

                    <div>
                        <h3 style="margin-bottom: 2rem; font-weight: 800;">Wishlist Snippet</h3>
                        <div style="display: grid; gap: 1rem;">
                            @forelse($wishlistItems as $item)
                                <a href="{{ route('products.show', $item->product) }}" style="text-decoration: none; color: inherit;">
                                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 1.25rem; padding: 1.25rem; display: flex; gap: 1rem; align-items: center; transition: 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.04)'">
                                        <img src="{{ $item->product->getPreviewUrl() }}" style="width: 70px; height: 45px; border-radius: 0.75rem; object-fit: cover;">
                                        <div style="font-size: 0.9rem; font-weight: 700; flex: 1;">{{ Str::limit($item->product->title, 30) }}</div>
                                        <i class="fa-solid fa-heart" style="color: #ef4444;"></i>
                                    </div>
                                </a>
                            @empty
                                <p style="color: var(--text-muted); text-align: center;">Empty.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            @elseif($tab == 'downloads')
                <h1 class="tab-title">Download <span>History</span></h1>
                <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 2rem; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background: rgba(255,255,255,0.03); color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em;">
                            <tr>
                                <th style="padding: 1.5rem 2rem; text-align: left;">Asset</th>
                                <th style="padding: 1.5rem 2rem; text-align: left;">Date</th>
                                <th style="padding: 1.5rem 2rem; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.95rem;">
                            @foreach($downloads as $log)
                                <tr style="border-bottom: 1px solid var(--glass-border);">
                                    <td style="padding: 1.5rem 2rem;">
                                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                                            <img src="{{ $log->product->getPreviewUrl() }}" style="width: 50px; height: 50px; border-radius: 0.75rem; object-fit: cover;">
                                            <span style="font-weight: 700;">{{ $log->product->title }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 1.5rem 2rem; color: var(--text-muted);">{{ $log->downloaded_at->format('M d, Y') }}</td>
                                    <td style="padding: 1.5rem 2rem; text-align: right;">
                                        <a href="{{ route('products.download', $log->product) }}" class="btn btn-primary" style="padding: 0.6rem 1.5rem; font-size: 0.8rem;">Download</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 2.5rem;">{{ $downloads->links() }}</div>

            @elseif($tab == 'collections')
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
                    <h1 class="tab-title" style="margin-bottom: 0;">My <span>Collections</span></h1>
                    <button @click="openCollectionModal = true" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                        <i class="fa-solid fa-plus" style="margin-right: 0.5rem;"></i> New Collection
                    </button>
                </div>

                <div class="collection-grid">
                    @forelse($collections as $collection)
                        <div class="collection-card">
                            <div class="collection-preview">
                                @foreach($collection->products->take(4) as $prod)
                                    <img src="{{ $prod->getPreviewUrl() }}" class="preview-img">
                                @endforeach
                                @if($collection->products->count() == 0)
                                    <div class="preview-img" style="grid-column: span 2; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-folder-open" style="font-size: 2rem; opacity: 0.1;"></i>
                                    </div>
                                @endif
                            </div>
                            <h3 style="font-weight: 800; margin-bottom: 0.5rem;">{{ $collection->name }}</h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 0.8rem;">
                                <span>{{ $collection->products->count() }} Items</span>
                                <span>{{ $collection->is_public ? 'Public' : 'Private' }}</span>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: span 3; text-align: center; padding: 5rem; background: rgba(255,255,255,0.01); border: 2px dashed var(--glass-border); border-radius: 2.5rem;">
                            <i class="fa-solid fa-folder-plus" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1.5rem;"></i>
                            <p style="color: var(--text-muted);">You haven't created any collections yet.</p>
                        </div>
                    @endforelse
                </div>

            @elseif($tab == 'settings')
                <h1 class="tab-title">Account <span>Settings</span></h1>
                <form action="{{ route('dashboard.profile.update') }}" method="POST" style="max-width: 600px;">
                    @csrf
                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.75rem; color: var(--text-muted); font-weight: 600;">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div style="margin-bottom: 3rem;">
                        <label style="display: block; margin-bottom: 0.75rem; color: var(--text-muted); font-weight: 600;">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(168, 85, 247, 0.05)); border: 1px solid var(--glass-border); border-radius: 2rem; padding: 2.5rem; margin-bottom: 3rem;">
                        <h3 style="margin-bottom: 1.5rem;">Current Subscription</h3>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-size: 1.5rem; font-weight: 900; color: {{ $user->isPremium() ? 'var(--accent)' : 'white' }}">
                                    {{ $user->subscription_type?->label() ?? 'Free' }}
                                </div>
                                <p style="font-size: 0.875rem; color: var(--text-muted); margin-top: 0.5rem;">Enjoy unlimited access to premium assets.</p>
                            </div>
                            @if(!$user->isPremium())
                                <a href="{{ route('checkout') }}" class="btn btn-primary" style="padding: 1rem 2rem;">Upgrade Now</a>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="padding: 1.25rem 3.5rem; font-size: 1.125rem; font-weight: 800; border-radius: 1.25rem;">Save Profile Changes</button>
                </form>
            @endif
        </main>
    </div>

    <!-- Collection Modal -->
    <template x-if="openCollectionModal">
        <div class="modal-overlay" @click.self="openCollectionModal = false">
            <div class="modal-content">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
                    <h2 style="font-size: 2rem; font-weight: 900;">New <span>Collection</span></h2>
                    <button @click="openCollectionModal = false" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.5rem;">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('dashboard.collections.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.75rem; color: var(--text-muted); font-weight: 600;">Collection Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. My Next Project" required autofocus>
                    </div>
                    
                    <div style="margin-bottom: 3rem; display: flex; align-items: center; gap: 1rem;">
                        <input type="checkbox" name="is_public" id="is_public" style="width: 20px; height: 20px; accent-color: var(--primary);">
                        <label for="is_public" style="font-weight: 600; cursor: pointer;">Make this collection public</label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.25rem; font-size: 1.1rem; font-weight: 800; border-radius: 1.25rem;">
                        Create Collection
                    </button>
                </form>
            </div>
        </div>
    </template>

    @include('layouts.footer')
</body>
</html>
