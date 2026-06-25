<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        $tenant = auth()->user()->tenant;

        $settings = [
            'midtrans_server_key' => $tenant->getSetting('midtrans_server_key'),
            'midtrans_client_key' => $tenant->getSetting('midtrans_client_key'),
            'midtrans_is_production' => $tenant->getSetting('midtrans_is_production', 'false') === 'true',
            
            'ai_provider' => $tenant->getSetting('ai_provider', 'openai'),
            'ai_provider_1' => $tenant->getSetting('ai_provider_1', 'openai'),
            'ai_provider_2' => $tenant->getSetting('ai_provider_2', 'openai'),
            'ai_provider_3' => $tenant->getSetting('ai_provider_3', 'openai'),
            'ai_provider_keyword' => $tenant->getSetting('ai_provider_keyword', 'openai'),
            
            'openai_api_key' => $tenant->getSetting('openai_api_key'),
            'openai_model' => $tenant->getSetting('openai_model', 'gpt-4o'),
            
            'gemini_api_key' => $tenant->getSetting('gemini_api_key'),
            'gemini_model' => $tenant->getSetting('gemini_model', 'gemini-1.5-pro'),
            
            'claude_api_key' => $tenant->getSetting('claude_api_key'),
            'claude_model' => $tenant->getSetting('claude_model', 'claude-3-5-sonnet'),
            
            'openrouter_api_key' => $tenant->getSetting('openrouter_api_key'),
            'openrouter_model' => $tenant->getSetting('openrouter_model', 'meta-llama/llama-3-8b-instruct'),
            'openrouter_api_base' => $tenant->getSetting('openrouter_api_base', 'https://openrouter.ai/api/v1'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Save/Update setting keys.
     */
    public function update(Request $request)
    {
        $request->validate([
            'midtrans_server_key' => 'nullable|string|max:255',
            'midtrans_client_key' => 'nullable|string|max:255',
            'midtrans_merchant_id' => 'nullable|string|max:255',
            'midtrans_is_production' => 'required|string|in:true,false',
            
            'ai_provider' => 'required|string|in:openai,gemini,claude,openrouter',
            'ai_provider_1' => 'required|string|in:openai,gemini,claude,openrouter',
            'ai_provider_2' => 'required|string|in:openai,gemini,claude,openrouter',
            'ai_provider_3' => 'required|string|in:openai,gemini,claude,openrouter',
            'ai_provider_4' => 'required|string|in:openai,gemini,claude,openrouter',
            'ai_provider_image_prompt' => 'required|string|in:openai,gemini,claude,openrouter',
            'ai_provider_keyword' => 'required|string|in:openai,gemini,claude,openrouter',
            
            'openai_api_key' => 'nullable|string|max:255',
            'openai_model' => 'nullable|string|max:255',
            
            'gemini_api_key' => 'nullable|string|max:255',
            'gemini_model' => 'nullable|string|max:255',
            
            'claude_api_key' => 'nullable|string|max:255',
            'claude_model' => 'nullable|string|max:255',
            
            'openrouter_api_key' => 'nullable|string|max:255',
            'openrouter_model' => 'nullable|string|max:255',
            'openrouter_api_base' => 'nullable|url|max:255',
        ]);

        $tenant = auth()->user()->tenant;

        // Save Midtrans configuration keys
        $tenant->setSetting('midtrans_server_key', $request->midtrans_server_key);
        $tenant->setSetting('midtrans_client_key', $request->midtrans_client_key);
        $tenant->setSetting('midtrans_merchant_id', $request->midtrans_merchant_id);
        $tenant->setSetting('midtrans_is_production', $request->midtrans_is_production);

        // Save AI settings
        $tenant->setSetting('ai_provider', $request->ai_provider);
        $tenant->setSetting('ai_provider_1', $request->ai_provider_1);
        $tenant->setSetting('ai_provider_2', $request->ai_provider_2);
        $tenant->setSetting('ai_provider_3', $request->ai_provider_3);
        $tenant->setSetting('ai_provider_4', $request->ai_provider_4);
        $tenant->setSetting('ai_provider_image_prompt', $request->ai_provider_image_prompt);
        $tenant->setSetting('ai_provider_keyword', $request->ai_provider_keyword);
        $tenant->setSetting('openai_api_key', $request->openai_api_key);
        $tenant->setSetting('openai_model', $request->openai_model ?: 'gpt-4o');
        
        $tenant->setSetting('gemini_api_key', $request->gemini_api_key);
        $tenant->setSetting('gemini_model', $request->gemini_model ?: 'gemini-1.5-pro');

        $tenant->setSetting('claude_api_key', $request->claude_api_key);
        $tenant->setSetting('claude_model', $request->claude_model ?: 'claude-3-5-sonnet');

        $tenant->setSetting('openrouter_api_key', $request->openrouter_api_key);
        $tenant->setSetting('openrouter_model', $request->openrouter_model ?: 'meta-llama/llama-3-8b-instruct');
        $tenant->setSetting('openrouter_api_base', $request->openrouter_api_base ?: 'https://openrouter.ai/api/v1');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Global system settings updated successfully!');
    }
}
