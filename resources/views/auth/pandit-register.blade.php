<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register as Pandit - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-orange-50 to-gray-50 font-[Inter] min-h-screen">
    <div class="max-w-3xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="text-center mb-10">
            <a href="/" class="text-3xl font-[Outfit] font-bold text-gray-900 inline-flex items-center gap-2 mb-4">
                <i class="fa-solid fa-om text-orange-500"></i> {{ config('app.name', 'Ved Mitra') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Register as a Pandit</h1>
            <p class="text-gray-500 mt-1">Join our platform and serve devotees across India</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-8 text-sm border border-red-100">
                <p class="font-bold mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pandit.register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- ─── PERSONAL DETAILS ─── -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><i class="fa-regular fa-user text-orange-500"></i> Personal Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alternate Phone</label>
                        <input type="tel" name="alternate_phone" value="{{ old('alternate_phone') }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                            <option value="">Select</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="8" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" required minlength="8" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                        <input type="file" name="profile_photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100">
                    </div>
                </div>
            </div>

            <!-- ─── LOCATION DETAILS ─── -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><i class="fa-solid fa-map-location-dot text-orange-500"></i> Location Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Address <span class="text-red-500">*</span></label>
                        <textarea name="address" required rows="2" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">{{ old('address') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                        <input type="text" name="city" value="{{ old('city') }}" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State <span class="text-red-500">*</span></label>
                        <input type="text" name="state" value="{{ old('state') }}" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pincode <span class="text-red-500">*</span></label>
                        <input type="text" name="pincode" value="{{ old('pincode') }}" required maxlength="6" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                        <input type="number" step="any" name="location_lat" value="{{ old('location_lat') }}" placeholder="e.g. 28.6139" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                        <input type="number" step="any" name="location_lng" value="{{ old('location_lng') }}" placeholder="e.g. 77.2090" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                </div>
            </div>

            <!-- ─── PROFESSIONAL DETAILS ─── -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><i class="fa-solid fa-briefcase text-orange-500"></i> Professional Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Years of Experience <span class="text-red-500">*</span></label>
                        <input type="number" name="experience_years" value="{{ old('experience_years') }}" required min="0" max="60" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Qualification <span class="text-red-500">*</span></label>
                        <input type="text" name="qualification" value="{{ old('qualification') }}" required placeholder="e.g. Shastri, Acharya" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Specialization <span class="text-red-500">*</span></label>
                        <input type="text" name="specialization" value="{{ old('specialization') }}" required placeholder="e.g. Vedic Rituals, Astrology" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Languages Known <span class="text-red-500">*</span></label>
                        <input type="text" name="languages" value="{{ old('languages') }}" required placeholder="Hindi, English, Sanskrit" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Consultation Fee (₹) <span class="text-red-500">*</span></label>
                        <input type="number" name="consultation_fee" value="{{ old('consultation_fee') }}" required min="0" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Available Timings</label>
                        <input type="text" name="available_timings" value="{{ old('available_timings') }}" placeholder="e.g. 8 AM - 6 PM" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">About / Bio <span class="text-red-500">*</span></label>
                        <textarea name="bio" required rows="3" maxlength="2000" placeholder="Describe your experience, training, and services..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">{{ old('bio') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Services / Pujas Offered <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($services as $service)
                                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200 hover:border-orange-400 cursor-pointer transition">
                                    <input type="checkbox" name="services[]" value="{{ $service->id }}" {{ in_array($service->id, old('services', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">{{ $service->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- ─── DOCUMENTS ─── -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><i class="fa-solid fa-file-shield text-orange-500"></i> Document Verification</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Aadhaar Card <span class="text-red-500">*</span></label>
                        <input type="file" name="aadhaar_document" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, or PDF (max 5MB)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PAN Card</label>
                        <input type="file" name="pan_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Certificates / Qualifications</label>
                        <input type="file" name="certificate_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100">
                    </div>
                </div>
            </div>

            <!-- ─── SOCIAL LINKS ─── -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2"><i class="fa-solid fa-globe text-orange-500"></i> Social Links <span class="text-xs text-gray-400 font-normal">(Optional)</span></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input type="url" name="website_url" value="{{ old('website_url') }}" placeholder="https://" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">YouTube Channel</label>
                        <input type="url" name="youtube_url" value="{{ old('youtube_url') }}" placeholder="https://youtube.com/..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                        <input type="url" name="instagram_url" value="{{ old('instagram_url') }}" placeholder="https://instagram.com/..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                        <input type="url" name="facebook_url" value="{{ old('facebook_url') }}" placeholder="https://facebook.com/..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-500/30 transition text-lg flex items-center justify-center gap-2">
                <i class="fa-solid fa-paper-plane"></i> Submit Application
            </button>
            <p class="text-center text-sm text-gray-500 mt-4">Already have an account? <a href="{{ route('login') }}" class="text-orange-600 font-bold hover:underline">Log in</a></p>
        </form>
    </div>
</body>
</html>
