<link rel="preload" href="{{ url('dist/css/adminlte.css') }}" as="style" />
<!--end::Accessibility Features-->

<!--begin::Fonts-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
    onload="this.media = 'all'" />
<!--end::Fonts-->

<!--begin::Third Party Plugin(OverlayScrollbars)-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
    crossorigin="anonymous" />
<!--end::Third Party Plugin(OverlayScrollbars)-->

<!--begin::Third Party Plugin(Bootstrap Icons)-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    crossorigin="anonymous" />
<!--end::Third Party Plugin(Bootstrap Icons)-->

<!--begin::Required Plugin(AdminLTE)-->
<link rel="stylesheet" href="{{ url('dist/css/adminlte.css') }}" />
<!--end::Required Plugin(AdminLTE)-->

<!--begin::Required Plugin(Quill)-->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<!--end::Required Plugin(Quill)-->

@stack('styles')
