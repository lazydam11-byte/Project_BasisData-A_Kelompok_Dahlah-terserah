<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">
{{-- Tailwind via CDN agar langsung jalan tanpa npm. Untuk produksi, pindahkan ke Vite. --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: { DEFAULT: '#00843D', dark: '#00692F', soft: '#E8F4EC' },
                    aksen: '#E8601C',
                },
                fontFamily: {
                    sans: ['"Open Sans"', 'sans-serif'],
                    display: ['Roboto', 'sans-serif'],
                },
            },
        },
    }
</script>
