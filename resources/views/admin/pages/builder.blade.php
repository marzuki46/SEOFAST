@extends('layouts.admin')

@section('title', 'Page Builder - ' . $page->title)

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">
<style>
    #gjs { border: 3px solid #444; }
    /* Reset some of the admin styles from affecting GrapesJS */
    .gjs-cv-canvas { top: 0; width: 100%; height: 100%; }
</style>
@endsection

@section('admin_content')
<div class="mb-4 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold font-outfit">Visual Builder: {{ $page->title }}</h2>
        <p class="text-sm text-gray-500">Slug: /{{ $page->slug }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 bg-gray-200 rounded shadow text-sm font-bold">Back</a>
        <button id="save-builder" class="px-4 py-2 bg-indigo-600 text-white rounded shadow text-sm font-bold hover:bg-indigo-500">Save Page</button>
    </div>
</div>

<!-- GrapesJS Container -->
<div id="gjs" style="height: 700px;"></div>

@endsection

@section('scripts')
<script src="https://unpkg.com/grapesjs"></script>
<script src="https://unpkg.com/grapesjs-preset-webpage@1.0.2"></script>
<script src="https://unpkg.com/grapesjs-blocks-basic@1.0.1"></script>
<script src="https://unpkg.com/grapesjs-plugin-forms@2.0.5"></script>
<script src="https://unpkg.com/grapesjs-blocks-flexbox@1.0.1"></script>
<script src="https://unpkg.com/grapesjs-custom-code@1.0.1"></script>
<script src="{{ asset('js/page-builder-blocks.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editor = grapesjs.init({
        container: '#gjs',
        height: '100%',
        width: 'auto',
        fromElement: true,
        storageManager: false, // We handle saving manually
        canvas: {
            scripts: ['https://cdn.tailwindcss.com']
        },
        plugins: ['gjs-preset-webpage', 'gjs-blocks-basic', 'grapesjs-plugin-forms', 'gjs-blocks-flexbox', 'grapesjs-custom-code'],
        pluginsOpts: {
            'gjs-preset-webpage': {
                blocksBasicOpts: { flexGrid: 1 }
            }
        }
    });

    // Register 20+ Tailwind pre-built sections (like Elementor widgets)
    registerTailwindBlocks(editor);

    // Load existing data if any
    let existingData = {!! $page->builder_data ? json_encode($page->builder_data) : 'null' !!};
    if (existingData && existingData !== 'null') {
        try {
            // sometimes the data is stored as a stringified json, sometimes as an object
            let parsed = typeof existingData === 'string' ? JSON.parse(existingData) : existingData;
            editor.loadProjectData(parsed);
        } catch (e) {
            console.error('Failed to load builder data', e);
        }
    }

    // Save logic
    document.getElementById('save-builder').addEventListener('click', function () {
        const btn = this;
        btn.innerText = 'Saving...';
        
        const html = editor.getHtml();
        const css = editor.getCss();
        const components = editor.getProjectData(); // Get full JSON structure

        fetch("{{ route('admin.pages.builder.save', $page->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                html: html,
                css: css,
                components: JSON.stringify(components)
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.innerText = 'Saved!';
                setTimeout(() => btn.innerText = 'Save Page', 2000);
            } else {
                alert('Failed to save page');
                btn.innerText = 'Save Page';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error saving page');
            btn.innerText = 'Save Page';
        });
    });
});
</script>
@endsection
