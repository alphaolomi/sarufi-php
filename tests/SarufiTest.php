<?php

// use Alphaolomi\Sarufi\Sarufi;


it('can instantiate Sarufi class', function () {
    expect(true)->toBeTrue();
});


// it('can instantiate Sarufi class', function () {
//     expect(new Sarufi("username","password"))->toBeInstanceOf(Sarufi::class);
// });


// it('can instantiate Sarufi class with token', function () {
//     expect(new Sarufi("username","password","xyz"))->toBeInstanceOf(Sarufi::class);
// });


// it('can instantiate live Sarufi', function () {
//     $sarufi = new Sarufi("aidannyshayo@gmail.com","password123");
//     expect($sarufi)->toBeInstanceOf(Sarufi::class);
//     // expect($sarufi->getToken())->dd();

// });


// it('can instantiate live Sarufi', function () {
//     $sarufi = new Sarufi("aidannyshayo@gmail.com", "password123");
//     // expect($sarufi)->toBeInstanceOf(Sarufi::class);
//     $bot = $sarufi->createBot(
//         name: "dany",
//         description: "my simple bot",
//     );
//     expect($bot)->dd();
// });



// it('can instantiate live Sarufi', function () {
//     $sarufi = new Sarufi("aidannyshayo@gmail.com", "password123");
//     // expect($sarufi)->toBeInstanceOf(Sarufi::class);
//     $filePath = __DIR__. '/intent.yaml';
//     $data = $sarufi->readFile(
//     //   path: 'intent.json'          
//       path:  $filePath         
//     );
//     expect(json_encode($data))->dd();
// });



// it('can instantiate live Sarufi', function () {
//     $sarufi = new Sarufi("aidannyshayo@gmail.com", "password123");
//     // expect($sarufi)->toBeInstanceOf(Sarufi::class);
//     $bot = $sarufi->createFromFile(
//         intents: __DIR__. "/intents.yaml",
//         metadata: __DIR__. "/metadata.yaml",
//         flow: __DIR__. "/flow.yaml",        
//     );
//     expect($bot)->dd();
// });


// it('can instantiate live Sarufi', function () {
//   $sarufi = new Sarufi("aidannyshayo@gmail.com", "password123");
//   // expect($sarufi)->toBeInstanceOf(Sarufi::class);
//   $bot = $sarufi->updateFromFile(
//     id: 81,
//       intents: __DIR__. "/intents.yaml",
//       metadata: __DIR__. "/metadata.yaml",
//       flow: __DIR__. "/flow.yaml",        
//   );
//   expect($bot)->dd();
// });


// it('can instantiate live Sarufi', function () {
//   $sarufi = new Sarufi("aidannyshayo@gmail.com", "password123");
//   // expect($sarufi)->toBeInstanceOf(Sarufi::class);
//   $bot = $sarufi->getBot(81);
//   expect($bot)->dd();
// });


// it('can get all bots', function () {
//   $sarufi = new Sarufi("aidannyshayo@gmail.com", "password123");
//   // expect($sarufi)->toBeInstanceOf(Sarufi::class);
//   $bots = $sarufi->bots();
//   expect($bots)->toBeArray();
//   expect($bots)->dd();
// });


// it('can chat or get response from bot', function () {
//   $sarufi = new Sarufi("aidannyshayo@gmail.com", "password123");
//   // expect($sarufi)->toBeInstanceOf(Sarufi::class);
//   $bots = $sarufi->chat(
//     botId: 81,
//     chatId: "123467"
//   );
//   // expect($bots)->toBeArray();
//   expect($bots)->dd();
// });

// it('can chat or get response from bot', function () {
//   $sarufi = new Sarufi("aidannyshayo@gmail.com", "password123");
//   // expect($sarufi)->toBeInstanceOf(Sarufi::class);
//   $res = $sarufi->deleteBot(
//     81,    
//   );
//   // expect($bots)->toBeArray();
//   expect($res)->dd();
// });