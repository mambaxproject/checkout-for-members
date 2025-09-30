<?php

return [

    'guarantee' => [
        [
            'name'  => '7 dias',
            'cycle' => 'day',
            'value' => 7,
        ],
        [
            'name'  => '14 dias',
            'cycle' => 'day',
            'value' => 14,
        ],
        [
            'name'  => '21 dias',
            'cycle' => 'day',
            'value' => 21,
        ],
        [
            'name'  => '30 dias',
            'cycle' => 'day',
            'value' => 30,
        ],
    ],

    'renewsRecurringPayment' => [
        [
            'name'  => 'Até o cliente cancelar',
            'value' => 'CUSTOMER_CANCEL',
        ],
        [
            'name'  => 'Número fixo de cobranças',
            'value' => 'FIXED_QTY_CHARGES',
        ],
    ],

    'whenOfferUpSell' => [
        [
            'name'  => 'Após a finalização do pedido (somente para compras por cartão de crédito)',
            'value' => 'AFTER_ORDER_WITH_CREDIT_CARD',
        ],
    ],

    'whenAcceptUpSell' => [
        [
            'name'  => 'Redirecionar para a página de obrigado',
            'value' => 'REDIRECT_TO_THANKS_PAGE',
        ],
        [
            'name'  => 'Oferecer outro Upsell',
            'value' => 'OFFER_ANOTHER_UPSELL',
        ],
    ],

    'whenRejectUpSell' => [
        [
            'name'  => 'Redirecionar para a página de obrigado',
            'value' => 'REDIRECT_TO_THANKS_PAGE',
        ],
        [
            'name'  => 'Oferecer Downsell',
            'value' => 'OFFER_DOWNSELL',
        ],
    ],

    'rejectReasons' => [
        [
            'name'  => 'Violação de Direitos Autorais',
            'value' => 'COPYRIGHT_VIOLATION',
        ],
        [
            'name' => 'Conteúdo Inadequado ou Ilegal',
            'value' => 'INAPPROPRIATE_CONTENT',
        ],
        [
            'name' => 'Atividade Incomum ou Suspeita',
            'value' => 'UNUSUAL_ACTIVITY',
        ],
        [
            'name' => 'Não Conformidade com as Políticas da Plataforma',
            'value' => 'NON_COMPLIANCE_WITH_PLATFORM_POLICIES',
        ]
    ]

];
