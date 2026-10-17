{{-- resources/views/partials/tailwind.blade.php --}}
{{-- Palet & tipografi mengikuti style guide (public/css/tokens.css). --}}
<script src="https://cdn.tailwindcss.com"></script>
<script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "primary": "#3B82F6",
          "primary-container": "#1D4ED8",
          "on-primary": "#FFFFFF",
          "on-primary-container": "#93C5FD",
          "primary-fixed": "#DBEAFE",
          "primary-fixed-dim": "#93C5FD",
          "on-primary-fixed": "#1E3A8A",
          "on-primary-fixed-variant": "#1D4ED8",
          "secondary": "#2563EB",
          "secondary-container": "#DBEAFE",
          "on-secondary": "#FFFFFF",
          "on-secondary-container": "#1D4ED8",
          "secondary-fixed": "#DBEAFE",
          "secondary-fixed-dim": "#93C5FD",
          "on-secondary-fixed": "#1E3A8A",
          "on-secondary-fixed-variant": "#1D4ED8",
          "tertiary": "#0F2E5B",
          "tertiary-container": "#4338CA",
          "on-tertiary": "#FFFFFF",
          "on-tertiary-container": "#C7D2FE",
          "tertiary-fixed": "#E0E7FF",
          "tertiary-fixed-dim": "#A5B4FC",
          "on-tertiary-fixed": "#312E81",
          "on-tertiary-fixed-variant": "#4338CA",
          "surface": "#F8FAFC",
          "surface-dim": "#E2E8F0",
          "surface-bright": "#FFFFFF",
          "surface-container-lowest": "#FFFFFF",
          "surface-container-low": "#F8FAFC",
          "surface-low": "#F8FAFC",
          "surface-container": "#F1F5F9",
          "surface-container-high": "#E2E8F0",
          "surface-container-highest": "#CBD5E1",
          "surface-variant": "#E2E8F0",
          "on-surface": "#0F172A",
          "on-surface-variant": "#64748B",
          "inverse-surface": "#0F172A",
          "inverse-on-surface": "#F8FAFC",
          "outline": "#94A3B8",
          "outline-variant": "#E2E8F0",
          "background": "#F1F5F9",
          "on-background": "#0F172A",
          "surface-tint": "#3B82F6",
          "inverse-primary": "#93C5FD",
          "error": "#EF4444",
          "on-error": "#FFFFFF",
          "error-container": "#FEE2E2",
          "on-error-container": "#991B1B",
          "success": "#22C55E",
          "warning": "#F59E0B",
          "danger": "#EF4444",
          "info": "#3B82F6",
          "indigo": "#6366F1",
          "dark-900": "#0F2E5B",
          "dark-800": "#153F91",
          "dark-700": "#2563EB",
          "dark-600": "#3882F6",
        },
        borderRadius: {
          DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", full: "9999px",
        },
        spacing: {
          "space-xs": "0.25rem", "space-sm": "0.5rem", "space-md": "1rem",
          "space-lg": "1.5rem", "space-xl": "2.5rem",
          "gutter": "1.5rem", "gutter-mobile": "1rem",
          "margin-mobile": "1.25rem", "margin-tablet": "2rem", "margin": "4rem",
        },
        fontFamily: {
          "display": ["Inter"], "headline-lg": ["Inter"],
          "headline-md": ["Inter"], "headline-sm": ["Inter"],
          "headline-lg-mobile": ["Inter"], "display-mobile": ["Inter"],
          "body-lg": ["Inter"], "body-md": ["Inter"], "body-sm": ["Inter"],
          "title-lg": ["Inter"], "title-md": ["Inter"],
          "label-md": ["Inter"], "label-sm": ["Inter"],
        },
        fontSize: {
          "display": ["40px", { lineHeight: "48px", letterSpacing: "-0.02em", fontWeight: "700" }],
          "display-mobile": ["30px", { lineHeight: "38px", letterSpacing: "-0.015em", fontWeight: "700" }],
          "headline-lg": ["32px", { lineHeight: "40px", letterSpacing: "-0.015em", fontWeight: "600" }],
          "headline-lg-mobile": ["26px", { lineHeight: "34px", letterSpacing: "-0.01em", fontWeight: "600" }],
          "headline-md": ["24px", { lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "600" }],
          "headline-sm": ["20px", { lineHeight: "28px", letterSpacing: "-0.005em", fontWeight: "600" }],
          "title-lg": ["18px", { lineHeight: "26px", fontWeight: "600" }],
          "title-md": ["16px", { lineHeight: "24px", fontWeight: "600" }],
          "body-lg": ["16px", { lineHeight: "26px", fontWeight: "400" }],
          "body-md": ["16px", { lineHeight: "26px", fontWeight: "400" }],
          "body-sm": ["14px", { lineHeight: "20px", fontWeight: "400" }],
          "label-md": ["14px", { lineHeight: "20px", letterSpacing: "0.01em", fontWeight: "500" }],
          "label-sm": ["12px", { lineHeight: "16px", letterSpacing: "0.04em", fontWeight: "600" }],
        },
      },
    },
  };
</script>
<style>
  @layer base {
    html, body { margin: 0; padding: 0; }
    body { overscroll-behavior: none; }
    main > :first-child { margin-top: 0 !important; }
    main > :last-child { margin-bottom: 0 !important; }
  }
  ::-webkit-scrollbar { display: none; }
</style>
