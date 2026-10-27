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
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css"
    integrity="sha384-ecIckRi4QlKYya/FQUbBUjS4qp65jF/J87Guw5uzTbO1C1Jfa/6kYmd6dXUF6D7i"
    crossorigin="anonymous"
/>
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.bubble.css"
    integrity="sha384-1p1L5n/ntE3sHKd2FQZmckXnRjjkgWlN9qvfQ2rQuCXYhgCEhcmPSjjVcbttjWKu"
    crossorigin="anonymous"
/>
<!--end::Required Plugin(Quill)-->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.6.1/dist/css/tom-select.bootstrap5.min.css" integrity="sha384-qNqaCnsmyTrYVwmqv4/4PcwMK8ZFAQnYPpVjWor+6cX6rKsPhebzu8vO2J3s+VZg" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" integrity="sha384-RkASv+6KfBMW9eknReJIJ6b3UnjKOKC5bOUaNgIY778NFbQ8MtWq9Lr/khUgqtTt" crossorigin="anonymous" />

<style>
    .user-roles > li:first-child {
        padding-top: 10px;
    }

    .user-roles > li {
        padding: 0 10px;
    }

    .user-roles > li:nth-last-child(2){
        padding-bottom: 10px;
    }

    .post-contents, .ql-bubble>.ql-editor {
        border: none !important;
        padding: 0px;
        overflow-x: hidden;
    }

    .ql-container {
        font-family: inherit !important;
        font-size: inherit !important;
    }
</style>

@stack('styles')
