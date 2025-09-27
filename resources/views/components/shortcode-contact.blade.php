<div class="mt-5 px-4">

    @if(session('form_success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('form_success') }}</span>
        </div>
    @endif

    @if(session('form_error') || $errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">
                {{ session('form_error') ?? 'Please correct the errors below and try again.' }}
            </span>
        </div>
    @endif

    <form class="mx-auto mt-3" id="contact-form" action="{{ route('contact-us') }}" method="POST">
        @csrf
        <div class="mb-5">
            <label for="name" class="block mb-2 font-medium text-gray-900">Name</label>
            <input value="{{ old('name') }}" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="" required />
            @error('name')
                <div class="text-red-500 mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-5">
            <label for="email" class="block mb-2 font-medium text-gray-900">Email</label>
            <input value="{{ old('email') }}"  type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="" required />
            @error('email')
                <div class="text-red-500 mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-5">
            <label for="phone" class="block mb-2 font-medium text-gray-900">Contact number</label>
            <input value="{{ old('contact') }}"  type="tel" id="phone" name="contact" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            @error('contact')
                <div class="text-red-500 mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-5">
            <label for="message" class="block mb-2 font-medium text-gray-900">Message</label>
            <textarea id="message" name="message" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Your message here">{{ old('message') }}</textarea>
            @error('message')
                <div class="text-red-500 mt-1">{{ $message }}</div>
            @enderror
        </div>



        @error('g-recaptcha-response')
            <div class="text-red-500 mt-1">{{ $message }}</div>
        @enderror

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<button class="g-recaptcha text-white bg-primary mt-4 hover:bg-primary-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg w-full sm:w-auto px-5 py-3 text-center"
  data-sitekey="6Lecd9YrAAAAAG-81NlE2FlYsGiXLrkcL0D1HEC3"
  data-callback="onSubmit">
  Submit
</button>

<script>
  function onSubmit(token) {
    document.getElementById("contact-form").submit();
  }
</script>



    </form>
</div>