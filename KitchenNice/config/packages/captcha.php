<?php if (!class_exists('CaptchaConfiguration')) { return; }

// BotDetect PHP Captcha configuration options

return [
    // Captcha configuration for recette.new
    'RecetteCaptcha' => [
        'UserInputID' => 'captchaCode',
        'ImageWidth' => 250,
        'ImageHeight' => 50,
    ],

    'CahierCaptcha' => [
        'UserInputID' => 'captchaCode',
        'ImageWidth' => 250,
        'ImageHeight' => 50,
    ],

    'LoginCaptcha' => [
        'UserInputID' => 'captchaCode',
        'CodeLength' => CaptchaRandomization::GetRandomCodeLength(4, 6),
        'ImageStyle' => [
            ImageStyle::Radar,
            ImageStyle::Collage,
            ImageStyle::Fingerprints,
        ],
    ],

    'TestCaptcha' => [
        ""
    ],

    '' => [
        ""
    ]

];