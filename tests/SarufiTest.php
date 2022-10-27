<?php

use Alphaolomi\Sarufi\Sarufi;

it('can instantiate Sarufi class', function () {
    expect(new Sarufi)->toBeInstanceOf(Sarufi::class);
});
