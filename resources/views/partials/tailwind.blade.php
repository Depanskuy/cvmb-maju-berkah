{{-- resources/views/partials/tailwind.blade.php --}}
<script src="https://cdn.tailwindcss.com"></script>
<script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "primary": "#00355f",
          "primary-container": "#0f4c81",
          "on-primary": "#ffffff",
          "on-primary-container": "#8ebdf9",
          "primary-fixed": "#d2e4ff",
          "primary-fixed-dim": "#a0c9ff",
          "on-primary-fixed": "#001c37",
          "on-primary-fixed-variant": "#07497d",
          "secondary": "#835500",
          "secondary-container": "#feae2c",
          "on-secondary": "#ffffff",
          "on-secondary-container": "#6b4500",
          "secondary-fixed": "#ffddb4",
          "secondary-fixed-dim": "#ffb955",
          "on-secondary-fixed": "#291800",
          "on-secondary-fixed-variant": "#633f00",
          "tertiary": "#293447",
          "tertiary-container": "#404b5e",
          "on-tertiary": "#ffffff",
          "on-tertiary-container": "#b0bbd2",
          "tertiary-fixed": "#d8e3fb",
          "tertiary-fixed-dim": "#bcc7de",
          "on-tertiary-fixed": "#111c2d",
          "on-tertiary-fixed-variant": "#3c475a",
          "surface": "#f9f9ff",
          "surface-dim": "#d3daee",
          "surface-bright": "#f9f9ff",
          "surface-container-lowest": "#ffffff",
          "surface-container-low": "#f1f3ff",
          "surface-container": "#e9edff",
          "surface-container-high": "#e2e8fc",
          "surface-container-highest": "#dce2f6",
          "surface-variant": "#dce2f6",
          "on-surface": "#151b2a",
          "on-surface-variant": "#42474f",
          "inverse-surface": "#2a3040",
          "inverse-on-surface": "#edf0ff",
          "outline": "#727780",
          "outline-variant": "#c2c7d1",
          "background": "#f9f9ff",
          "on-background": "#151b2a",
          "surface-tint": "#2d6197",
          "inverse-primary": "#a0c9ff",
          "error": "#ba1a1a",
          "on-error": "#ffffff",
          "error-container": "#ffdad6",
          "on-error-container": "#93000a",
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
          "display": ["Outfit"], "headline-lg": ["Outfit"],
          "headline-md": ["Outfit"], "headline-sm": ["Outfit"],
          "headline-lg-mobile": ["Outfit"], "display-mobile": ["Outfit"],
          "body-lg": ["Inter"], "body-md": ["Inter"], "body-sm": ["Inter"],
          "title-lg": ["Inter"], "title-md": ["Inter"],
          "label-md": ["Inter"], "label-sm": ["Inter"],
        },
        fontSize: {
          "display": ["56px", { lineHeight: "64px", letterSpacing: "-0.02em", fontWeight: "700" }],
          "display-mobile": ["38px", { lineHeight: "46px", letterSpacing: "-0.015em", fontWeight: "700" }],
          "headline-lg": ["40px", { lineHeight: "48px", letterSpacing: "-0.015em", fontWeight: "700" }],
          "headline-lg-mobile": ["28px", { lineHeight: "36px", letterSpacing: "-0.01em", fontWeight: "700" }],
          "headline-md": ["30px", { lineHeight: "38px", letterSpacing: "-0.01em", fontWeight: "600" }],
          "headline-sm": ["22px", { lineHeight: "30px", letterSpacing: "-0.005em", fontWeight: "600" }],
          "title-lg": ["18px", { lineHeight: "26px", fontWeight: "600" }],
          "title-md": ["16px", { lineHeight: "24px", fontWeight: "600" }],
          "body-lg": ["18px", { lineHeight: "28px", fontWeight: "400" }],
          "body-md": ["15px", { lineHeight: "24px", fontWeight: "400" }],
          "body-sm": ["13px", { lineHeight: "20px", fontWeight: "400" }],
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