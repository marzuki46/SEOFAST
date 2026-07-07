function registerTailwindBlocks(editor) {
  const blockManager = editor.BlockManager;
  const cat = 'Tailwind Sections';

  const blocks = [

    // ─── HERO ────────────────────────────────────────────
    {
      id: 'tw-hero',
      label: 'Hero',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
    <div class="mr-auto place-self-center lg:col-span-7">
      <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">Build better products</h1>
      <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">From idea to launch, we give you everything you need to build products that users love.</p>
      <a href="#" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-sm font-medium text-center text-white rounded-lg bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-900">Get started <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></a>
      <a href="#" class="inline-flex items-center justify-center px-5 py-3 text-sm font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Learn More</a>
    </div>
    <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
      <img src="https://placehold.co/600x500/e2e8f0/64748b?text=Hero+Image" alt="hero">
    </div>
  </div>
</section>`
    },

    // ─── FEATURES GRID ────────────────────────────────────
    {
      id: 'tw-features',
      label: 'Features Grid',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
    <div class="max-w-screen-md mb-8 lg:mb-16">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Everything you need</h2>
      <p class="text-gray-500 sm:text-xl dark:text-gray-400">All the tools you need to scale your business.</p>
    </div>
    <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0">
      <div class="text-center">
        <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-indigo-100 lg:h-12 lg:w-12 dark:bg-indigo-900 mx-auto">
          <svg class="w-5 h-5 text-indigo-600 lg:w-6 lg:h-6 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3z"></path></svg>
        </div>
        <h3 class="mb-2 text-xl font-bold dark:text-white">Feature One</h3>
        <p class="text-gray-500 dark:text-gray-400">Powerful features to help you manage and grow your business efficiently.</p>
      </div>
      <div class="text-center">
        <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-indigo-100 lg:h-12 lg:w-12 dark:bg-indigo-900 mx-auto">
          <svg class="w-5 h-5 text-indigo-600 lg:w-6 lg:h-6 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.256.748a1 1 0 001.932-.518l-.256-.748zM2.126 5.872a1 1 0 00.246 1.378l.603.452a1 1 0 101.132-1.648l-.603-.452a1 1 0 00-1.378.246zm13.51-1.508a1 1 0 00-.246-1.378 1 1 0 00-1.378.246l-.452.603a1 1 0 001.648 1.132l.452-.603zM10 4a6 6 0 00-6 6 1 1 0 002 0 4 4 0 014-4 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
        </div>
        <h3 class="mb-2 text-xl font-bold dark:text-white">Feature Two</h3>
        <p class="text-gray-500 dark:text-gray-400">Stay ahead with intelligent insights and automation.</p>
      </div>
      <div class="text-center">
        <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-indigo-100 lg:h-12 lg:w-12 dark:bg-indigo-900 mx-auto">
          <svg class="w-5 h-5 text-indigo-600 lg:w-6 lg:h-6 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
        </div>
        <h3 class="mb-2 text-xl font-bold dark:text-white">Feature Three</h3>
        <p class="text-gray-500 dark:text-gray-400">Reliable and secure platform you can count on.</p>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── CTA ──────────────────────────────────────────
    {
      id: 'tw-cta',
      label: 'CTA',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-sm text-center">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold leading-tight text-gray-900 dark:text-white">Ready to get started?</h2>
      <p class="mb-6 font-light text-gray-500 dark:text-gray-400 md:text-lg">Join thousands of satisfied customers. No credit card required.</p>
      <a href="#" class="text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none dark:focus:ring-indigo-800">Start Free Trial</a>
      <a href="#" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-indigo-800">Contact Sales</a>
    </div>
  </div>
</section>`
    },

    // ─── PRICING TABLE ─────────────────────────────────
    {
      id: 'tw-pricing',
      label: 'Pricing Table',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-12">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Simple, transparent pricing</h2>
      <p class="mb-5 font-light text-gray-500 sm:text-xl dark:text-gray-400">Choose the plan that fits your needs.</p>
    </div>
    <div class="space-y-8 lg:grid lg:grid-cols-3 sm:gap-6 xl:gap-10 lg:space-y-0">
      <div class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
        <h3 class="mb-4 text-2xl font-semibold">Starter</h3>
        <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">Best for individuals</p>
        <div class="flex justify-center items-baseline my-8">
          <span class="mr-2 text-5xl font-extrabold">$29</span>
          <span class="text-gray-500 dark:text-gray-400">/month</span>
        </div>
        <ul role="list" class="mb-8 space-y-4 text-left">
          <li class="flex items-center space-x-3"><svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><span>1 user</span></li>
          <li class="flex items-center space-x-3"><svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><span>All core features</span></li>
          <li class="flex items-center space-x-3"><svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><span>5GB storage</span></li>
        </ul>
        <a href="#" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-white dark:focus:ring-indigo-900">Choose plan</a>
      </div>
      <div class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
        <h3 class="mb-4 text-2xl font-semibold">Professional</h3>
        <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">Best for teams</p>
        <div class="flex justify-center items-baseline my-8">
          <span class="mr-2 text-5xl font-extrabold">$79</span>
          <span class="text-gray-500 dark:text-gray-400">/month</span>
        </div>
        <ul role="list" class="mb-8 space-y-4 text-left">
          <li class="flex items-center space-x-3"><svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><span>Up to 10 users</span></li>
          <li class="flex items-center space-x-3"><svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><span>Priority support</span></li>
          <li class="flex items-center space-x-3"><svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><span>50GB storage</span></li>
        </ul>
        <a href="#" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-white dark:focus:ring-indigo-900">Choose plan</a>
      </div>
      <div class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
        <h3 class="mb-4 text-2xl font-semibold">Enterprise</h3>
        <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">Best for large companies</p>
        <div class="flex justify-center items-baseline my-8">
          <span class="mr-2 text-5xl font-extrabold">$199</span>
          <span class="text-gray-500 dark:text-gray-400">/month</span>
        </div>
        <ul role="list" class="mb-8 space-y-4 text-left">
          <li class="flex items-center space-x-3"><svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><span>Unlimited users</span></li>
          <li class="flex items-center space-x-3"><svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><span>Dedicated support</span></li>
          <li class="flex items-center space-x-3"><svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><span>500GB storage</span></li>
        </ul>
        <a href="#" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-white dark:focus:ring-indigo-900">Choose plan</a>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── TESTIMONIALS ───────────────────────────────
    {
      id: 'tw-testimonials',
      label: 'Testimonials',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md mb-8 lg:mb-12">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Trusted by teams worldwide</h2>
    </div>
    <div class="grid gap-8 lg:grid-cols-3">
      <figure class="p-6 bg-gray-50 rounded-xl dark:bg-gray-800">
        <p class="text-gray-500 dark:text-gray-400 italic">"This product has completely transformed how our team works. The results speak for themselves."</p>
        <div class="flex items-center mt-4 space-x-3">
          <img class="w-10 h-10 rounded-full" src="https://placehold.co/40x40/6366f1/ffffff?text=S" alt="avatar">
          <div class="space-y-0.5 font-medium dark:text-white text-left">
            <div>Sarah Johnson</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">CEO, TechCorp</div>
          </div>
        </div>
      </figure>
      <figure class="p-6 bg-gray-50 rounded-xl dark:bg-gray-800">
        <p class="text-gray-500 dark:text-gray-400 italic">"Incredible value for the price. We saw a 300% increase in productivity within the first month."</p>
        <div class="flex items-center mt-4 space-x-3">
          <img class="w-10 h-10 rounded-full" src="https://placehold.co/40x40/6366f1/ffffff?text=M" alt="avatar">
          <div class="space-y-0.5 font-medium dark:text-white text-left">
            <div>Michael Chen</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">CTO, StartupXYZ</div>
          </div>
        </div>
      </figure>
      <figure class="p-6 bg-gray-50 rounded-xl dark:bg-gray-800">
        <p class="text-gray-500 dark:text-gray-400 italic">"The support team is absolutely amazing. They go above and beyond every single time."</p>
        <div class="flex items-center mt-4 space-x-3">
          <img class="w-10 h-10 rounded-full" src="https://placehold.co/40x40/6366f1/ffffff?text=A" alt="avatar">
          <div class="space-y-0.5 font-medium dark:text-white text-left">
            <div>Amanda Lee</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">PM, DesignLab</div>
          </div>
        </div>
      </figure>
    </div>
  </div>
</section>`
    },

    // ─── FAQ ACCORDION ──────────────────────────────
    {
      id: 'tw-faq',
      label: 'FAQ Accordion',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Frequently asked questions</h2>
    </div>
    <div class="max-w-screen-md mx-auto space-y-4">
      <div class="border border-gray-200 rounded-lg dark:border-gray-700">
        <button class="flex items-center justify-between w-full p-5 font-medium text-left text-gray-900 bg-white rounded-t-lg focus:outline-none dark:text-white dark:bg-gray-800" onclick="this.nextElementSibling.classList.toggle('hidden');this.querySelector('svg').classList.toggle('rotate-180')">
          <span>What is the pricing structure?</span>
          <svg class="w-5 h-5 shrink-0 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
        <div class="hidden p-5 border-t border-gray-200 dark:border-gray-700">
          <p class="text-gray-500 dark:text-gray-400">We offer three pricing tiers: Starter ($29/mo), Professional ($79/mo), and Enterprise ($199/mo). Each tier includes a different set of features and usage limits.</p>
        </div>
      </div>
      <div class="border border-gray-200 rounded-lg dark:border-gray-700">
        <button class="flex items-center justify-between w-full p-5 font-medium text-left text-gray-900 bg-white rounded-t-lg focus:outline-none dark:text-white dark:bg-gray-800" onclick="this.nextElementSibling.classList.toggle('hidden');this.querySelector('svg').classList.toggle('rotate-180')">
          <span>Can I cancel anytime?</span>
          <svg class="w-5 h-5 shrink-0 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
        <div class="hidden p-5 border-t border-gray-200 dark:border-gray-700">
          <p class="text-gray-500 dark:text-gray-400">Yes, you can cancel your subscription at any time. No cancellation fees. Your data will remain accessible for the remainder of your billing period.</p>
        </div>
      </div>
      <div class="border border-gray-200 rounded-lg dark:border-gray-700">
        <button class="flex items-center justify-between w-full p-5 font-medium text-left text-gray-900 bg-white rounded-t-lg focus:outline-none dark:text-white dark:bg-gray-800" onclick="this.nextElementSibling.classList.toggle('hidden');this.querySelector('svg').classList.toggle('rotate-180')">
          <span>Do you offer refunds?</span>
          <svg class="w-5 h-5 shrink-0 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
        <div class="hidden p-5 border-t border-gray-200 dark:border-gray-700">
          <p class="text-gray-500 dark:text-gray-400">We offer a 30-day money-back guarantee for all new subscriptions. If you're not satisfied, contact our support team for a full refund.</p>
        </div>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── TEAM GRID ─────────────────────────────────
    {
      id: 'tw-team',
      label: 'Team Grid',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Meet our team</h2>
      <p class="text-gray-500 sm:text-xl dark:text-gray-400">We are a passionate group of people building great things.</p>
    </div>
    <div class="grid gap-8 lg:grid-cols-4 sm:grid-cols-2">
      <div class="text-center">
        <img class="mx-auto mb-4 w-28 h-28 rounded-full" src="https://placehold.co/120x120/6366f1/ffffff?text=JD" alt="avatar">
        <h3 class="text-lg font-bold dark:text-white">John Doe</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">CEO & Founder</p>
      </div>
      <div class="text-center">
        <img class="mx-auto mb-4 w-28 h-28 rounded-full" src="https://placehold.co/120x120/6366f1/ffffff?text=JW" alt="avatar">
        <h3 class="text-lg font-bold dark:text-white">Jane Wilson</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">CTO</p>
      </div>
      <div class="text-center">
        <img class="mx-auto mb-4 w-28 h-28 rounded-full" src="https://placehold.co/120x120/6366f1/ffffff?text=MK" alt="avatar">
        <h3 class="text-lg font-bold dark:text-white">Mike Kumar</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Lead Designer</p>
      </div>
      <div class="text-center">
        <img class="mx-auto mb-4 w-28 h-28 rounded-full" src="https://placehold.co/120x120/6366f1/ffffff?text=LG" alt="avatar">
        <h3 class="text-lg font-bold dark:text-white">Lisa Garcia</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Marketing Lead</p>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── STATS COUNTER ─────────────────────────────
    {
      id: 'tw-stats',
      label: 'Stats Counter',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="max-w-screen-xl px-4 py-8 mx-auto text-center lg:py-16 lg:px-6">
    <dl class="grid max-w-screen-md gap-8 mx-auto text-gray-900 sm:grid-cols-3 dark:text-white">
      <div class="flex flex-col items-center justify-center">
        <dt class="mb-2 text-4xl font-extrabold">10K+</dt>
        <dd class="text-gray-500 dark:text-gray-400">Happy Customers</dd>
      </div>
      <div class="flex flex-col items-center justify-center">
        <dt class="mb-2 text-4xl font-extrabold">500+</dt>
        <dd class="text-gray-500 dark:text-gray-400">Projects Delivered</dd>
      </div>
      <div class="flex flex-col items-center justify-center">
        <dt class="mb-2 text-4xl font-extrabold">99.9%</dt>
        <dd class="text-gray-500 dark:text-gray-400">Uptime</dd>
      </div>
    </dl>
  </div>
</section>`
    },

    // ─── LOGO CLOUD ────────────────────────────────
    {
      id: 'tw-logos',
      label: 'Logo Cloud',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <h2 class="mb-8 text-3xl font-extrabold tracking-tight text-center text-gray-900 dark:text-white">Trusted by leading companies</h2>
    <div class="grid grid-cols-2 gap-8 text-gray-500 sm:gap-12 sm:grid-cols-3 lg:grid-cols-6 dark:text-gray-400">
      <div class="flex justify-center items-center h-16 bg-gray-100 rounded-lg dark:bg-gray-700">
        <span class="text-xl font-bold text-gray-400">LOGO</span>
      </div>
      <div class="flex justify-center items-center h-16 bg-gray-100 rounded-lg dark:bg-gray-700">
        <span class="text-xl font-bold text-gray-400">LOGO</span>
      </div>
      <div class="flex justify-center items-center h-16 bg-gray-100 rounded-lg dark:bg-gray-700">
        <span class="text-xl font-bold text-gray-400">LOGO</span>
      </div>
      <div class="flex justify-center items-center h-16 bg-gray-100 rounded-lg dark:bg-gray-700">
        <span class="text-xl font-bold text-gray-400">LOGO</span>
      </div>
      <div class="flex justify-center items-center h-16 bg-gray-100 rounded-lg dark:bg-gray-700">
        <span class="text-xl font-bold text-gray-400">LOGO</span>
      </div>
      <div class="flex justify-center items-center h-16 bg-gray-100 rounded-lg dark:bg-gray-700">
        <span class="text-xl font-bold text-gray-400">LOGO</span>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── ICON BOX ─────────────────────────────────
    {
      id: 'tw-iconbox',
      label: 'Icon Box',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-12">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Why choose us</h2>
    </div>
    <div class="grid gap-8 lg:grid-cols-3">
      <div class="p-6 bg-gray-50 rounded-2xl dark:bg-gray-800">
        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 dark:bg-indigo-900">
          <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <h3 class="text-xl font-bold dark:text-white mb-2">Lightning Fast</h3>
        <p class="text-gray-500 dark:text-gray-400">Built for speed. Your pages will load in milliseconds.</p>
      </div>
      <div class="p-6 bg-gray-50 rounded-2xl dark:bg-gray-800">
        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 dark:bg-indigo-900">
          <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        <h3 class="text-xl font-bold dark:text-white mb-2">Secure & Reliable</h3>
        <p class="text-gray-500 dark:text-gray-400">Enterprise-grade security with 99.9% uptime guarantee.</p>
      </div>
      <div class="p-6 bg-gray-50 rounded-2xl dark:bg-gray-800">
        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 dark:bg-indigo-900">
          <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        <h3 class="text-xl font-bold dark:text-white mb-2">Expert Support</h3>
        <p class="text-gray-500 dark:text-gray-400">24/7 support from real experts who care about your success.</p>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── GALLERY GRID ──────────────────────────────
    {
      id: 'tw-gallery',
      label: 'Gallery Grid',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Our Gallery</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
      <div class="h-48 bg-gray-200 rounded-xl dark:bg-gray-700 overflow-hidden">
        <img src="https://placehold.co/600x400/6366f1/ffffff?text=Image+1" class="w-full h-full object-cover" alt="gallery">
      </div>
      <div class="h-48 bg-gray-200 rounded-xl dark:bg-gray-700 overflow-hidden">
        <img src="https://placehold.co/600x400/818cf8/ffffff?text=Image+2" class="w-full h-full object-cover" alt="gallery">
      </div>
      <div class="h-48 bg-gray-200 rounded-xl dark:bg-gray-700 overflow-hidden">
        <img src="https://placehold.co/600x400/6366f1/ffffff?text=Image+3" class="w-full h-full object-cover" alt="gallery">
      </div>
      <div class="h-48 bg-gray-200 rounded-xl dark:bg-gray-700 overflow-hidden">
        <img src="https://placehold.co/600x400/818cf8/ffffff?text=Image+4" class="w-full h-full object-cover" alt="gallery">
      </div>
      <div class="h-48 bg-gray-200 rounded-xl dark:bg-gray-700 overflow-hidden">
        <img src="https://placehold.co/600x400/6366f1/ffffff?text=Image+5" class="w-full h-full object-cover" alt="gallery">
      </div>
      <div class="h-48 bg-gray-200 rounded-xl dark:bg-gray-700 overflow-hidden">
        <img src="https://placehold.co/600x400/818cf8/ffffff?text=Image+6" class="w-full h-full object-cover" alt="gallery">
      </div>
    </div>
  </div>
</section>`
    },

    // ─── PROGRESS BAR ─────────────────────────────
    {
      id: 'tw-progress',
      label: 'Progress Bars',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
    <div class="max-w-screen-md mx-auto">
      <h2 class="mb-8 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white text-center">Our Skills</h2>
      <div class="mb-6">
        <div class="flex justify-between mb-1"><span class="text-sm font-medium text-gray-900 dark:text-white">Web Design</span><span class="text-sm font-medium text-gray-500">95%</span></div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700"><div class="bg-indigo-600 h-2.5 rounded-full" style="width:95%"></div></div>
      </div>
      <div class="mb-6">
        <div class="flex justify-between mb-1"><span class="text-sm font-medium text-gray-900 dark:text-white">Development</span><span class="text-sm font-medium text-gray-500">88%</span></div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700"><div class="bg-indigo-600 h-2.5 rounded-full" style="width:88%"></div></div>
      </div>
      <div class="mb-6">
        <div class="flex justify-between mb-1"><span class="text-sm font-medium text-gray-900 dark:text-white">Marketing</span><span class="text-sm font-medium text-gray-500">75%</span></div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700"><div class="bg-indigo-600 h-2.5 rounded-full" style="width:75%"></div></div>
      </div>
      <div>
        <div class="flex justify-between mb-1"><span class="text-sm font-medium text-gray-900 dark:text-white">SEO</span><span class="text-sm font-medium text-gray-500">92%</span></div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700"><div class="bg-indigo-600 h-2.5 rounded-full" style="width:92%"></div></div>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── TABS ──────────────────────────────────────
    {
      id: 'tw-tabs',
      label: 'Tabs',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Features</h2>
    </div>
    <div class="max-w-screen-md mx-auto">
      <div class="border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
          <li class="mr-2"><a href="#" class="inline-block p-4 text-indigo-600 border-b-2 border-indigo-600 rounded-t-lg active dark:text-indigo-500 dark:border-indigo-500" onclick="event.preventDefault();document.querySelectorAll('.tab-content').forEach(el=>el.classList.add('hidden'));document.getElementById('tab1').classList.remove('hidden');this.closest('ul').querySelectorAll('a').forEach(a=>a.className='inline-block p-4 text-gray-500 hover:text-gray-600 rounded-t-lg');this.className='inline-block p-4 text-indigo-600 border-b-2 border-indigo-600 rounded-t-lg active'">Tab 1</a></li>
          <li class="mr-2"><a href="#" class="inline-block p-4 text-gray-500 hover:text-gray-600 rounded-t-lg" onclick="event.preventDefault();document.querySelectorAll('.tab-content').forEach(el=>el.classList.add('hidden'));document.getElementById('tab2').classList.remove('hidden');this.closest('ul').querySelectorAll('a').forEach(a=>a.className='inline-block p-4 text-gray-500 hover:text-gray-600 rounded-t-lg');this.className='inline-block p-4 text-indigo-600 border-b-2 border-indigo-600 rounded-t-lg active'">Tab 2</a></li>
          <li class="mr-2"><a href="#" class="inline-block p-4 text-gray-500 hover:text-gray-600 rounded-t-lg" onclick="event.preventDefault();document.querySelectorAll('.tab-content').forEach(el=>el.classList.add('hidden'));document.getElementById('tab3').classList.remove('hidden');this.closest('ul').querySelectorAll('a').forEach(a=>a.className='inline-block p-4 text-gray-500 hover:text-gray-600 rounded-t-lg');this.className='inline-block p-4 text-indigo-600 border-b-2 border-indigo-600 rounded-t-lg active'">Tab 3</a></li>
        </ul>
      </div>
      <div id="tab1" class="tab-content p-6">
        <p class="text-gray-500 dark:text-gray-400">Tab 1 content. Click the tabs to switch between different content panels. Perfect for organizing feature descriptions or product details.</p>
      </div>
      <div id="tab2" class="tab-content hidden p-6">
        <p class="text-gray-500 dark:text-gray-400">Tab 2 content. Each tab panel can contain any HTML content including text, images, videos, and more.</p>
      </div>
      <div id="tab3" class="tab-content hidden p-6">
        <p class="text-gray-500 dark:text-gray-400">Tab 3 content. This pattern makes it easy to present large amounts of information in a clean, organized way.</p>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── CONTACT FORM ──────────────────────────────
    {
      id: 'tw-contact',
      label: 'Contact Form',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Get in touch</h2>
      <p class="text-gray-500 sm:text-xl dark:text-gray-400">We'd love to hear from you.</p>
    </div>
    <form action="#" class="max-w-screen-md mx-auto space-y-6">
      <div class="grid gap-6 sm:grid-cols-2">
        <div>
          <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Your Name</label>
          <input type="text" class="w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="John Doe">
        </div>
        <div>
          <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Your Email</label>
          <input type="email" class="w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="john@example.com">
        </div>
      </div>
      <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Subject</label>
        <input type="text" class="w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="How can we help?">
      </div>
      <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Your Message</label>
        <textarea rows="5" class="w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Write your message..."></textarea>
      </div>
      <button type="submit" class="w-full px-5 py-3 text-sm font-medium text-center text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900">Send Message</button>
    </form>
  </div>
</section>`
    },

    // ─── VIDEO EMBED ───────────────────────────────
    {
      id: 'tw-video',
      label: 'Video Embed',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Watch our demo</h2>
    </div>
    <div class="mx-auto max-w-screen-md aspect-video rounded-xl overflow-hidden shadow-lg bg-gray-200">
      <div class="w-full h-full flex items-center justify-center bg-gray-800 text-white">
        <div class="text-center">
          <svg class="w-16 h-16 mx-auto mb-2 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
          <p class="text-sm text-gray-400">Video Placeholder</p>
        </div>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── DIVIDER ──────────────────────────────────
    {
      id: 'tw-divider',
      label: 'Divider',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900 py-4">
  <div class="max-w-screen-xl mx-auto px-4">
    <div class="flex items-center">
      <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
      <span class="mx-4 text-gray-400 text-sm">✦</span>
      <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
    </div>
  </div>
</section>`
    },

    // ─── ALERT BOX ────────────────────────────────
    {
      id: 'tw-alert',
      label: 'Alert Box',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900 py-8">
  <div class="max-w-screen-md mx-auto px-4 space-y-4">
    <div class="flex p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
      <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
      <div><span class="font-medium">Info alert!</span> This is an informational message.</div>
    </div>
    <div class="flex p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
      <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
      <div><span class="font-medium">Success alert!</span> Action completed successfully.</div>
    </div>
    <div class="flex p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
      <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
      <div><span class="font-medium">Error alert!</span> Something went wrong.</div>
    </div>
  </div>
</section>`
    },

    // ─── TIMELINE ────────────────────────────────
    {
      id: 'tw-timeline',
      label: 'Timeline',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Our Journey</h2>
    </div>
    <div class="max-w-screen-md mx-auto">
      <ol class="relative border-l border-gray-200 dark:border-gray-700">
        <li class="mb-10 ml-6">
          <span class="absolute flex items-center justify-center w-6 h-6 bg-indigo-100 rounded-full -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-indigo-900"><svg class="w-3 h-3 text-indigo-600 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9z" clip-rule="evenodd"></path></svg></span>
          <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">Company Founded</h3>
          <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">2020</time>
          <p class="text-base font-normal text-gray-500 dark:text-gray-400">Our journey began with a simple mission: make great software accessible to everyone.</p>
        </li>
        <li class="mb-10 ml-6">
          <span class="absolute flex items-center justify-center w-6 h-6 bg-indigo-100 rounded-full -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-indigo-900"><svg class="w-3 h-3 text-indigo-600 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9z" clip-rule="evenodd"></path></svg></span>
          <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">First 100 Customers</h3>
          <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">2021</time>
          <p class="text-base font-normal text-gray-500 dark:text-gray-400">Reached our first milestone of 100 paying customers across all plans.</p>
        </li>
        <li class="ml-6">
          <span class="absolute flex items-center justify-center w-6 h-6 bg-indigo-100 rounded-full -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-indigo-900"><svg class="w-3 h-3 text-indigo-600 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9z" clip-rule="evenodd"></path></svg></span>
          <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">Global Expansion</h3>
          <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">2023</time>
          <p class="text-base font-normal text-gray-500 dark:text-gray-400">Expanded operations to 12 countries with a team of 50+ amazing people.</p>
        </li>
      </ol>
    </div>
  </div>
</section>`
    },

    // ─── PORTFOLIO CARD ──────────────────────────
    {
      id: 'tw-portfolio',
      label: 'Portfolio Card',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Our Work</h2>
      <p class="text-gray-500 sm:text-xl dark:text-gray-400">See some of our recent projects.</p>
    </div>
    <div class="grid gap-6 lg:grid-cols-3">
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <img class="w-full h-48 object-cover" src="https://placehold.co/600x400/6366f1/ffffff?text=Project+1" alt="project">
        <div class="p-5">
          <h3 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">E-Commerce Platform</h3>
          <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">A full-featured online store with payment integration.</p>
          <a href="#" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">View Project →</a>
        </div>
      </div>
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <img class="w-full h-48 object-cover" src="https://placehold.co/600x400/818cf8/ffffff?text=Project+2" alt="project">
        <div class="p-5">
          <h3 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">SaaS Dashboard</h3>
          <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">Analytics dashboard with real-time reporting.</p>
          <a href="#" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">View Project →</a>
        </div>
      </div>
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <img class="w-full h-48 object-cover" src="https://placehold.co/600x400/6366f1/ffffff?text=Project+3" alt="project">
        <div class="p-5">
          <h3 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">Mobile App Design</h3>
          <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">UI/UX design for a fitness tracking application.</p>
          <a href="#" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">View Project →</a>
        </div>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── COUNTDOWN ───────────────────────────────
    {
      id: 'tw-countdown',
      label: 'Countdown',
      category: cat,
      content: `<section class="bg-indigo-600 dark:bg-indigo-800">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-white">Coming Soon</h2>
      <p class="mb-8 text-indigo-200 sm:text-lg">Something amazing is on the way!</p>
      <div class="flex justify-center gap-4 sm:gap-8 text-white">
        <div class="text-center"><div class="text-5xl font-extrabold">30</div><div class="text-sm text-indigo-200">Days</div></div>
        <div class="text-center"><div class="text-5xl font-extrabold">12</div><div class="text-sm text-indigo-200">Hours</div></div>
        <div class="text-center"><div class="text-5xl font-extrabold">45</div><div class="text-sm text-indigo-200">Minutes</div></div>
        <div class="text-center"><div class="text-5xl font-extrabold">20</div><div class="text-sm text-indigo-200">Seconds</div></div>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── ABOUT TEXT ──────────────────────────────
    {
      id: 'tw-about',
      label: 'About Text',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="grid gap-8 lg:grid-cols-2 items-center">
      <div>
        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">About Our Company</h2>
        <p class="mb-4 text-gray-500 dark:text-gray-400">We are a team of passionate creators dedicated to building innovative solutions that make a real difference. Since our founding, we have helped hundreds of businesses transform their digital presence.</p>
        <p class="mb-4 text-gray-500 dark:text-gray-400">Our approach combines cutting-edge technology with thoughtful design to deliver experiences that users love and businesses rely on.</p>
        <a href="#" class="inline-flex items-center text-indigo-600 hover:underline dark:text-indigo-400 font-medium">Learn more about us →</a>
      </div>
      <div class="hidden lg:flex">
        <img src="https://placehold.co/600x400/6366f1/ffffff?text=About+Us" class="w-full rounded-xl shadow-lg" alt="about">
      </div>
    </div>
  </div>
</section>`
    },

    // ─── NEWSLETTER ──────────────────────────────
    {
      id: 'tw-newsletter',
      label: 'Newsletter',
      category: cat,
      content: `<section class="bg-gray-50 dark:bg-gray-800">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md sm:text-center">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Stay in the loop</h2>
      <p class="mx-auto mb-8 max-w-2xl font-light text-gray-500 md:mb-12 sm:text-xl dark:text-gray-400">Subscribe to our newsletter for tips, updates, and exclusive content.</p>
      <form action="#" class="items-center mx-auto mb-3 space-y-4 max-w-screen-sm sm:flex sm:space-y-0">
        <div class="relative w-full">
          <label class="hidden mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email</label>
          <input class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 sm:rounded-none sm:rounded-l-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter your email" type="email" id="email">
        </div>
        <button type="submit" class="w-full px-5 py-3 text-sm font-medium text-center text-white bg-indigo-600 rounded-lg border cursor-pointer sm:rounded-none sm:rounded-r-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">Subscribe</button>
      </form>
    </div>
  </div>
</section>`
    },

    // ─── 2-COLUMN CONTENT ─────────────────────────
    {
      id: 'tw-cols-2',
      label: '2 Columns Text',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="grid gap-8 lg:grid-cols-2">
      <div class="p-6">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Column One</h3>
        <p class="text-gray-500 dark:text-gray-400">This is a two-column text layout. Perfect for presenting paired content, side-by-side information, or feature comparisons. Each column can contain its own heading, text, images, or any HTML element.</p>
      </div>
      <div class="p-6">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Column Two</h3>
        <p class="text-gray-500 dark:text-gray-400">The columns are responsive and will stack on mobile devices. You can customize the gap, background, padding, and more using the GrapesJS style manager on the right panel.</p>
      </div>
    </div>
  </div>
</section>`
    },

    // ─── GOOGLE MAPS ─────────────────────────────
    {
      id: 'tw-map',
      label: 'Map',
      category: cat,
      content: `<section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
    <div class="mx-auto max-w-screen-md text-center mb-8">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Find us</h2>
    </div>
    <div class="mx-auto max-w-screen-md h-80 bg-gray-200 rounded-xl dark:bg-gray-700 flex items-center justify-center">
      <div class="text-center text-gray-500">
        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <p>Map Placeholder</p>
      </div>
    </div>
  </div>
</section>`
    },

  ];

  blocks.forEach(b => blockManager.add(b.id, b));

  // ─── POSTS GRID (dynamic server-rendered component) ───────────
  editor.DomComponents.addType('seofast-posts', {
    model: {
      defaults: {
        tagName: 'div',
        attributes: {
          'class': 'seofast-posts-grid',
          'data-columns': '3',
          'data-limit': '6',
        },
        draggable: true,
        droppable: false,
        traits: [
          {
            type: 'select',
            name: 'data-columns',
            label: 'Columns',
            options: [
              { value: 1, name: '1 Column' },
              { value: 2, name: '2 Columns' },
              { value: 3, name: '3 Columns' },
              { value: 4, name: '4 Columns' },
              { value: 6, name: '6 Columns' },
            ],
          },
          {
            type: 'number',
            name: 'data-limit',
            label: 'Max Posts',
            min: 1,
            max: 50,
          },
        ],
      },
    },
    view: {
      init() {
        this.updatePlaceholder();
        this.listenTo(this.model, 'change:attributes', this.updatePlaceholder);
      },
      updatePlaceholder() {
        const attrs = this.model.getAttributes();
        const cols = attrs['data-columns'] || 3;
        const limit = attrs['data-limit'] || 6;
        this.el.innerHTML =
          `<div class="p-8 text-center border-2 border-dashed border-indigo-300 rounded-xl bg-indigo-50">` +
          `<svg class="w-10 h-10 mx-auto mb-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>` +
          `<div class="font-bold text-indigo-600">Posts Grid</div>` +
          `<div class="text-xs text-gray-400">` + cols + ` Column(s) &middot; ` + limit + ` Posts</div>` +
          `</div>`;
      },
    },
  });

  blockManager.add('seofast-posts', {
    label: 'Posts Grid',
    category: 'Tailwind Sections',
    content: {
      type: 'seofast-posts',
      attributes: {
        'class': 'seofast-posts-grid',
        'data-columns': '3',
        'data-limit': '6',
      },
    },
  });

  // ─── PRODUCTS GRID (dynamic server-rendered component) ─────────
  editor.DomComponents.addType('seofast-products', {
    model: {
      defaults: {
        tagName: 'div',
        attributes: {
          'class': 'seofast-products-grid',
          'data-columns': '3',
          'data-limit': '6',
        },
        draggable: true,
        droppable: false,
        traits: [
          {
            type: 'select',
            name: 'data-columns',
            label: 'Columns',
            options: [
              { value: 1, name: '1 Column' },
              { value: 2, name: '2 Columns' },
              { value: 3, name: '3 Columns' },
              { value: 4, name: '4 Columns' },
              { value: 6, name: '6 Columns' },
            ],
          },
          {
            type: 'number',
            name: 'data-limit',
            label: 'Max Products',
            min: 1,
            max: 50,
          },
        ],
      },
    },
    view: {
      init() {
        this.updatePlaceholder();
        this.listenTo(this.model, 'change:attributes', this.updatePlaceholder);
      },
      updatePlaceholder() {
        const attrs = this.model.getAttributes();
        const cols = attrs['data-columns'] || 3;
        const limit = attrs['data-limit'] || 6;
        this.el.innerHTML =
          `<div class="p-8 text-center border-2 border-dashed border-emerald-300 rounded-xl bg-emerald-50">` +
          `<svg class="w-10 h-10 mx-auto mb-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>` +
          `<div class="font-bold text-emerald-600">Products Grid</div>` +
          `<div class="text-xs text-gray-400">` + cols + ` Column(s) &middot; ` + limit + ` Products</div>` +
          `</div>`;
      },
    },
  });

  blockManager.add('seofast-products', {
    label: 'Products Grid',
    category: 'Tailwind Sections',
    content: {
      type: 'seofast-products',
      attributes: {
        'class': 'seofast-products-grid',
        'data-columns': '3',
        'data-limit': '6',
      },
    },
  });
}
