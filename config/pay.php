<?php

return [
    'alipay' => [
        'app_id'         => '2016091800540646',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvjqvyIyNhn59S6xbv0FjiqMVGU1nAqRoFGtwU+xpAog3vL0xnH5Zzn1dwW1+Tc651y1fiNIddfWWsF7utUv7QwMwe5x+SFr8mOHnA3Xr2lY3hUKgZ1087ZNu4Btwo9PswysREO4yyGVjD6PKqduu5Qh1Wj0fnbo8VkVm0CpORcvk0S2EI99oAki5zrlYMlVFH+R7INw83bAxZdpQ+rhkVgegC3O/xGRDB8hYKeR2yBsNTx9AwfMvJYy1KkruaUefxiJ8dbUYr61MGfLTs164MTMA9qzxuw7ytiQmrJ3Dq49/z/TxFUASwwvPDaxCzfwCdFxkiXyHKvJWB0p1PLkVCQIDAQAB',
        'private_key'    => 'MIIEogIBAAKCAQEA5pXWbC0qpUeMgidfYR7SEXJlxcj92WBmM2tx73fkov9CuoXqlzaJGHygye51McXkLgXFVEK1l9eb0I8QEQxLzFDS85S51pXGEa/O2hrzFjDDPW9mvU+IHVnVUKVpaaX43ZHDbJnAV/Veh3E/JdepQbz6Ah4jxaSAJNHt+nkoaVDRjBE8ETnlLyUxxU9vnaQQqjmkjIEnbaXSay4zoZzVAk1QaupGWK0gp83gU6445M/JDhPPBw3x5Ag6vPRzW6Galp5u1vEncnP15+yD38lvFFWb5B/ufL/Xw+s+Sf8ZO85YijZMtMzwOnZHNgI0wAZGF2D7idBf0EaRK8UvnS0zwQIDAQABAoIBAGsoBtWOx9q3PVze3zxLDmR9PT6FATMb0WLj87bNiLsW1PPJeBN9jIiMokw1PNE8naWlmOZneexujkKFuXmytSbmCKKZ4yMNx1ZA1WgWThFp59GNC7m+VAsPzEoGFM68CsMtEhxN90zpAlz/C9GSP14FbEm/p/AZWlPCG03jMByASHsYH4COzvFF40Xm27C2hOP2e1u7Guz/IZq0Bl7lCxqfPKJjt1lhoDiyUsQKW4o/CTgyZoFz7ryhSsij2aqcx9ytdyk4cyik/FytyJ9IdIfg4IMwYkbJIQ30Fr7HQ3B8F8ANFfKVltW5ZCzYCntYBIDbEs49/EF9DDThuSmtv+ECgYEA+Y8CP4RpStf1hCCwvB+vvAPDb+qfiihh2KonN/+u4ORbPftTSFHb3JAeaZ3iufu70+1Bmjy4FM4Y9Jq9jR1lXRinPK+s3z5s4CU7iPcaDLF95fRQg/6WNR74jbSmErKof/ZbCLoVS6vcSU8czaIXm/vDZbm8Uk8RZyeEj2sK+98CgYEA7Il1yBf1QWAW06vugc1Iz1nGTK/yxeKq8FMI/cKDsL2huJVg2IdQRwghagPBaZ9bNSbGhD204On03DUN7ZXvbRbEh0vIplYelBlZ/QSmV2wk53Y5e2DQvUfn0uvXSsqDcs0+IcExi5Tadp7otBVfCAqJ/Hvy4VRYN8kYOm0dxF8CgYBT9Oistb82jrDqYpUStRZKCnoVjLlfoXZJjfTjwgDzZ9/KWmMKUX/GFDYnEhbUuvvVsFwBp6vGVA/ROr3KW7leOI0KvY9LC5VVUzFUQs1gt9XDJw5vWZbvCBxWkA/O+ov1gMvfg7rmWksh3puyEnYe4/Q4pOPWTQNt0L2oAjsPwQKBgDcuZkzU0gF7YjflX1HmyzqcTfesAG4L0Ccap/lTPJep1aWTK7G395FqoyjxUVjLtWJz9lH5d04VJmuM8P/hP6bqbdTGaDvt0Vppg6XURN4WO5HH8ecHkgrmUH3TSTmIfxv0J6+GO4G9qy2LKuyAI9hjZQeC/wl0PNRe1Ikk1ZHvAoGAZ79bxKODE4MSK9PuxUzXp7oik5U0MEgiAMKDbvVclO9SGEEy4xfGHTBqtYxCWdhtZWHQe4INs/ZGYzA6CEmFk8jh5aMEh+/dtLCj71sL4pVTjBZOz1OlnmBD0dr3H57AahgKHjdiDD3ev85fWpCaDwFJ5zHI7K5+dD1TWx66lcU=',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
