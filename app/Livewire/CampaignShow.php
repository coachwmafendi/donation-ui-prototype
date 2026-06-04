<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;

class CampaignShow extends Component
{
    public Campaign $campaign;

    public bool $showArchiveModal = false;

    // Settings: Payment Methods
    public array $paymentMethods = [];

    // Settings: Currency
    public string $currency = 'USD';

    // Settings: Frequencies
    public array $frequencies = [];

    public string $defaultFrequency = 'one-time';

    // Settings: Suggested Amounts (presets per frequency)
    public array $frequencyPresets = [];

    public array $defaultAmounts = [];

    // Settings: Minimum Amounts
    public array $minAmounts = [];

    // Settings: Transaction Cost
    public bool $coverFee = true;

    // Page Config: Appearance
    public string $pagePrimaryColor = 'emerald';

    public bool $pageDarkHero = true;

    // Page Config: Visibility Toggles
    public bool $pageShowProgressBar = true;

    public bool $pageShowDonorCount = true;

    public bool $pageShowGoalAmount = true;

    public bool $pageShowDaysLeft = true;

    public bool $pageShowRecentSupporters = true;

    public bool $pageShowCampaignDetails = true;

    public bool $pageShowEmbedCode = true;

    public bool $pageShowBottomCta = true;

    // Page Config: Text Customization
    public string $pageHeroHeadline = '';

    public string $pageHeroSubheadline = '';

    public string $pagePrimaryCtaText = 'Donate Now';

    public string $pageSecondaryCtaText = 'Learn More';

    public string $pageSecondaryCtaLink = '#about';

    public string $pageBottomCtaHeadline = 'Ready to make an impact?';

    public string $pageBottomCtaBody = '';

    public string $pageRecentSupportersHeading = 'Recent supporters';

    public string $pageAboutHeading = 'About this campaign';

    // Page Config: Supporter Privacy
    public bool $pageShowAnonymousDonors = true;

    public bool $pageShowDonationAmounts = true;

    public bool $pageShowDonorAvatars = true;

    // Page Config: SEO / Sharing
    public ?string $pageSeoTitle = null;

    public ?string $pageSeoDescription = null;

    public ?string $pageSocialShareImage = null;

    // Page Config: Advanced
    public ?string $pageCustomCss = null;

    public ?string $pageCustomJs = null;

    public ?string $pageRedirectAfterDonation = null;

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign->loadCount('donations');

        $settings = $campaign->settings ?? [];

        $this->currency = $campaign->currency ?? 'USD';

        $this->paymentMethods = $settings['payment_methods'] ?? ['credit_card', 'paypal'];

        $this->frequencies = $settings['frequencies'] ?? ['one-time', 'monthly'];
        $this->defaultFrequency = $settings['default_frequency'] ?? 'one-time';

        $this->defaultAmounts = $settings['default_amounts'] ?? [
            'one-time' => 50,
            'monthly' => 25,
            'yearly' => 300,
            'weekly' => 10,
            'quarterly' => 75,
        ];

        // Per-frequency presets
        $this->frequencyPresets = $settings['frequency_presets'] ?? [
            'one-time' => [200, 100, 50, 30, 10, 5],
            'monthly' => [50, 25, 10],
        ];

        // Ensure all configured frequencies have preset arrays
        foreach ($this->frequencies as $freq) {
            if (! isset($this->frequencyPresets[$freq]) || ! is_array($this->frequencyPresets[$freq])) {
                $this->frequencyPresets[$freq] = [50, 25, 10];
            }
        }

        $this->minAmounts = $settings['min_amounts'] ?? [
            'one-time' => 10,
            'monthly' => 10,
            'yearly' => 100,
            'weekly' => 5,
            'quarterly' => 25,
        ];

        $this->coverFee = $settings['cover_fee'] ?? true;

        // Page Config
        $pageConfig = $settings['page_config'] ?? [];

        // Appearance
        $this->pagePrimaryColor = $pageConfig['primary_color'] ?? 'emerald';
        $this->pageDarkHero = $pageConfig['dark_hero'] ?? true;

        // Visibility
        $this->pageShowProgressBar = $pageConfig['show_progress_bar'] ?? true;
        $this->pageShowDonorCount = $pageConfig['show_donor_count'] ?? true;
        $this->pageShowGoalAmount = $pageConfig['show_goal_amount'] ?? true;
        $this->pageShowDaysLeft = $pageConfig['show_days_left'] ?? true;
        $this->pageShowRecentSupporters = $pageConfig['show_recent_supporters'] ?? true;
        $this->pageShowCampaignDetails = $pageConfig['show_campaign_details'] ?? true;
        $this->pageShowEmbedCode = $pageConfig['show_embed_code'] ?? true;
        $this->pageShowBottomCta = $pageConfig['show_bottom_cta'] ?? true;

        // Text
        $this->pageHeroHeadline = $pageConfig['hero_headline'] ?? $campaign->name;
        $this->pageHeroSubheadline = $pageConfig['hero_subheadline'] ?? ($campaign->description ?? 'Your support makes a lasting impact.');
        $this->pagePrimaryCtaText = $pageConfig['primary_cta_text'] ?? 'Donate Now';
        $this->pageSecondaryCtaText = $pageConfig['secondary_cta_text'] ?? 'Learn More';
        $this->pageSecondaryCtaLink = $pageConfig['secondary_cta_link'] ?? '#about';
        $this->pageBottomCtaHeadline = $pageConfig['bottom_cta_headline'] ?? 'Ready to make an impact?';
        $this->pageBottomCtaBody = $pageConfig['bottom_cta_body'] ?? '';
        $this->pageRecentSupportersHeading = $pageConfig['recent_supporters_heading'] ?? 'Recent supporters';
        $this->pageAboutHeading = $pageConfig['about_heading'] ?? 'About this campaign';

        // Privacy
        $this->pageShowAnonymousDonors = $pageConfig['show_anonymous_donors'] ?? true;
        $this->pageShowDonationAmounts = $pageConfig['show_donation_amounts'] ?? true;
        $this->pageShowDonorAvatars = $pageConfig['show_donor_avatars'] ?? true;

        // SEO
        $this->pageSeoTitle = $pageConfig['seo_title'] ?? null;
        $this->pageSeoDescription = $pageConfig['seo_description'] ?? null;
        $this->pageSocialShareImage = $pageConfig['social_share_image'] ?? null;

        // Advanced
        $this->pageCustomCss = $pageConfig['custom_css'] ?? null;
        $this->pageCustomJs = $pageConfig['custom_js'] ?? null;
        $this->pageRedirectAfterDonation = $pageConfig['redirect_after_donation'] ?? null;
    }

    public function archive(): void
    {
        $this->campaign->update(['status' => 'archived']);
        $this->showArchiveModal = false;
        $this->dispatch('toast', message: 'Campaign archived.', type: 'success');
    }

    public function savePaymentMethods(): void
    {
        $this->saveSetting('payment_methods', $this->paymentMethods);
    }

    public function saveCurrency(): void
    {
        $this->campaign->update(['currency' => $this->currency]);
        $this->dispatch('toast', message: 'Currency saved.', type: 'success');
    }

    public function saveFrequencies(): void
    {
        $this->saveSetting('frequencies', $this->frequencies);
        $this->saveSetting('default_frequency', $this->defaultFrequency);
    }

    public function saveAmounts(): void
    {
        // Clean up empty/null values from each frequency's presets
        $cleanPresets = [];
        foreach ($this->frequencyPresets as $freq => $values) {
            $cleanPresets[$freq] = array_values(array_filter($values, fn ($v) => $v !== null && $v !== ''));
        }
        $this->saveSetting('frequency_presets', $cleanPresets);
        $this->saveSetting('default_amounts', $this->defaultAmounts);
    }

    public function saveMinimums(): void
    {
        $this->saveSetting('min_amounts', $this->minAmounts);
    }

    public function saveTransactionCost(): void
    {
        $this->saveSetting('cover_fee', $this->coverFee);
    }

    public function savePageConfig(): void
    {
        if (str_word_count($this->pageHeroSubheadline) > 90) {
            $this->addError('pageHeroSubheadline', 'The hero subheadline must not exceed 90 words.');

            return;
        }

        $this->saveSetting('page_config', [
            'primary_color' => $this->pagePrimaryColor,
            'dark_hero' => $this->pageDarkHero,
            'show_progress_bar' => $this->pageShowProgressBar,
            'show_donor_count' => $this->pageShowDonorCount,
            'show_goal_amount' => $this->pageShowGoalAmount,
            'show_days_left' => $this->pageShowDaysLeft,
            'show_recent_supporters' => $this->pageShowRecentSupporters,
            'show_campaign_details' => $this->pageShowCampaignDetails,
            'show_embed_code' => $this->pageShowEmbedCode,
            'show_bottom_cta' => $this->pageShowBottomCta,
            'hero_headline' => $this->pageHeroHeadline,
            'hero_subheadline' => $this->pageHeroSubheadline,
            'primary_cta_text' => $this->pagePrimaryCtaText,
            'secondary_cta_text' => $this->pageSecondaryCtaText,
            'secondary_cta_link' => $this->pageSecondaryCtaLink,
            'bottom_cta_headline' => $this->pageBottomCtaHeadline,
            'bottom_cta_body' => $this->pageBottomCtaBody,
            'recent_supporters_heading' => $this->pageRecentSupportersHeading,
            'about_heading' => $this->pageAboutHeading,
            'show_anonymous_donors' => $this->pageShowAnonymousDonors,
            'show_donation_amounts' => $this->pageShowDonationAmounts,
            'show_donor_avatars' => $this->pageShowDonorAvatars,
            'seo_title' => $this->pageSeoTitle,
            'seo_description' => $this->pageSeoDescription,
            'social_share_image' => $this->pageSocialShareImage,
            'custom_css' => $this->pageCustomCss,
            'custom_js' => $this->pageCustomJs,
            'redirect_after_donation' => $this->pageRedirectAfterDonation,
        ]);
    }

    protected function saveSetting(string $key, mixed $value): void
    {
        $settings = $this->campaign->settings ?? [];
        $settings[$key] = $value;
        $this->campaign->update(['settings' => $settings]);
        $this->dispatch('toast', message: str_replace('_', ' ', ucfirst($key)).' saved.', type: 'success');
    }

    public function render()
    {
        return view('livewire.campaign-show', [
            'recentDonations' => $this->campaign->donations()
                ->with('profile')
                ->latest()
                ->limit(5)
                ->get(),
        ])->layout('components.layouts.admin');
    }
}
