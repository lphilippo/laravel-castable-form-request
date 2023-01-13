<?php

namespace Tests\Provider;

use LPhilippo\CastableFormRequest\Provider\DefaultValueProvider;
use PHPUnit\Framework\TestCase as BaseTestCase;

class DefaultValueProviderTest extends BaseTestCase
{
    public function testDefaultValues()
    {
        $defaultValueProvider = new DefaultValueProvider();
        $defaults = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        $this->assertSame(
            $defaultValueProvider->apply(
                [
                    'key3' => 'value100',
                ],
                $defaults
            ),
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value100',
            ]
        );
    }

    public function testNestedValues()
    {
        $defaultValueProvider = new DefaultValueProvider();
        $defaults = [
            'key1.*.nested1' => 'value1',
            'key1.nested1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key4.*' => 'value4',
        ];

        $this->assertSame(
            $defaultValueProvider->apply(
                [
                    'key2' => 'value100',
                ],
                $defaults
            ),
            [
                'key1' => [
                    'nested1' => 'value1',
                ],
                'key2' => 'value100',
                'key3' => 'value3',
            ]
        );

        $this->assertSame(
            $defaultValueProvider->apply(
                [
                    'key1' => [
                        'nested1' => 'value100',
                        'nested2' => 'value200',
                    ],
                ],
                $defaults
            ),
            [
                'key1' => [
                    'nested1' => 'value100',
                    'nested2' => 'value200',
                ],
                'key2' => 'value2',
                'key3' => 'value3',
            ]
        );

        $this->assertSame(
            $defaultValueProvider->apply(
                [
                    'key1' => [
                        'nested2' => 'value200',
                    ],
                ],
                $defaults
            ),
            [
                'key1' => [
                    'nested1' => 'value1',
                    'nested2' => 'value200',
                ],
                'key2' => 'value2',
                'key3' => 'value3',
            ]
        );
    }

    public function testArrayValues()
    {
        $defaultValueProvider = new DefaultValueProvider();
        $defaults = [
            '*.nested1' => 'value1',
        ];

        $this->assertSame(
            $defaultValueProvider->apply(
                [
                    [
                        'nested2' => 'value200',
                    ],
                    [
                        'nested1' => 'value100',
                    ],
                ],
                $defaults
            ),
            [
                [
                    'nested1' => 'value1',
                    'nested2' => 'value200',
                ],
                [
                    'nested1' => 'value100',
                ],
            ],
        );
    }

    public function testMultiLevelNestedValues()
    {
        $defaultValueProvider = new DefaultValueProvider();
        $defaults = [
            '*.nested.*.nested1' => 'value1',
            '*.nested.*.nested2.nested3' => 'value3',
            '*.nested.*.nested2.nested4.*.nested5' => 'value5',
        ];

        $this->assertSame(
            $defaultValueProvider->apply(
                [
                    [
                        'nested' => [
                            [
                                'nested1' => 'value100',
                            ],
                        ],
                    ],
                    [
                        'nested' => [
                            [
                                'nested2' => [
                                    'nested3' => 'value300',
                                    'nested4' => [
                                        [
                                            'nested5' => 'value500',
                                        ],
                                        [
                                            'nested6' => 'value6',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                $defaults
            ),
            [
                [
                    'nested' => [
                        [
                            'nested1' => 'value100',
                            'nested2' => [
                                'nested3' => 'value3',
                            ],
                        ],
                    ],
                ],
                [
                    'nested' => [
                        [
                            'nested1' => 'value1',
                            'nested2' => [
                                'nested3' => 'value300',
                                'nested4' => [
                                    [
                                        'nested5' => 'value500',
                                    ],
                                    [
                                        'nested5' => 'value5',
                                        'nested6' => 'value6',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        );
    }
}
