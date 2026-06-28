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

    // Custom Tailwind Blocks
    const blockManager = editor.BlockManager;
    
    blockManager.add('tailwind-hero', {
        label: 'Hero Section',
        category: 'Tailwind Blocks',
        content: `
            <section class="bg-white dark:bg-gray-900">
                <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
                    <div class="mr-auto place-self-center lg:col-span-7">
                        <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">Payments tool for software companies</h1>
                        <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">From checkout to global sales tax compliance, companies around the world use Flowbite to simplify their payment stack.</p>
                        <a href="#" class="inline-flex items-center justify-center px-4 py-2.5 mr-3 text-sm font-medium text-center text-white rounded-lg bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-900">
                            Get started
                            <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </a>
                        <a href="#" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            Speak to Sales
                        </a> 
                    </div>
                    <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
                        <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/hero/phone-mockup.png" alt="mockup">
                    </div>                
                </div>
            </section>
        `
    });

    blockManager.add('tailwind-features', {
        label: 'Features',
        category: 'Tailwind Blocks',
        content: `
            <section class="bg-white dark:bg-gray-900">
              <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
                  <div class="max-w-screen-md mb-8 lg:mb-16">
                      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Designed for business teams like yours</h2>
                      <p class="text-gray-500 sm:text-xl dark:text-gray-400">Here at Flowbite we focus on markets where technology, innovation, and capital can unlock long-term value and drive economic growth.</p>
                  </div>
                  <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0">
                      <div>
                          <h3 class="mb-2 text-xl font-bold dark:text-white">Marketing</h3>
                          <p class="text-gray-500 dark:text-gray-400">Plan it, create it, launch it. Collaborate seamlessly with all the organization and hit your marketing goals every month with our marketing plan.</p>
                      </div>
                      <div>
                          <h3 class="mb-2 text-xl font-bold dark:text-white">Legal</h3>
                          <p class="text-gray-500 dark:text-gray-400">Protect your organization, devices and network from unapproved access with the highest level of security and protection.</p>
                      </div>
                      <div>
                          <h3 class="mb-2 text-xl font-bold dark:text-white">Business Automation</h3>
                          <p class="text-gray-500 dark:text-gray-400">Auto-assign tasks, send Slack messages, and much more. Now you can concentrate on your most important tasks.</p>
                      </div>
                  </div>
              </div>
            </section>
        `
    });

    blockManager.add('tailwind-cta', {
        label: 'Call to Action',
        category: 'Tailwind Blocks',
        content: `
            <section class="bg-white dark:bg-gray-900">
                <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
                    <div class="mx-auto max-w-screen-sm text-center">
                        <h2 class="mb-4 text-4xl tracking-tight font-extrabold leading-tight text-gray-900 dark:text-white">Start your free trial today</h2>
                        <p class="mb-6 font-light text-gray-500 dark:text-gray-400 md:text-lg">Try Flowbite Platform for 30 days. No credit card required.</p>
                        <a href="#" class="text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none dark:focus:ring-indigo-800">Free trial for 30 days</a>
                    </div>
                </div>
            </section>
        `
    });

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
