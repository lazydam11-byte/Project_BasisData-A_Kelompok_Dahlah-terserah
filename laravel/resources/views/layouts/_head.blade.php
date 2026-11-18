<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">
{{-- Tailwind via CDN agar langsung jalan tanpa npm. Untuk produksi, pindahkan ke Vite. --}}
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: {
                        DEFAULT: '#142D4E',
                        dark: '#0F2038',
                        soft: '#EAF0F8',
                    },
                    aksen: '#2563EB',
                    highlight: '#60A5FA',
                    promo: '#F97316',
                },

                fontFamily: {
                    sans: ['"Open Sans"', 'sans-serif'],
                    display: ['Roboto', 'sans-serif'],
                },
            },
        },
    }
</script>
