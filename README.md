# CakePHPMenuBuilder
A simple menu builder helper for CakePHP 3

## Setup

In View/AppView.php or in your Controller file, call MenuHelper in initialize() function

    public function initialize()
    {
      parent::initialize();
      $this->loadHelper('Menu');
    }

## Add Links (in view file)
    $this->Menu->init('main'); //optional line. Valid in case of multiple menus. Defines the area of the menu
    $this->Menu->addMenuItem(__('First Link'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addMenuItem(__('Second Link'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addMenuSubItem(__('Second Sub Link One'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addMenuSubItem(__('Second Sub Link Two'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addMenuItem(__('Third Link'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addMenuSubItem(__('Third Sub Link One'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addMenuSubItem(__('Third Sub Link Two'), ['action' => 'linkaction', $otherparams]);

## Call menu in layout

    echo $this->Menu->get('main');// optional argument, in case of multiple menus. just echo $this->Menu->get() for default is main.

That's it!

## Advanced options to add multilevel menu

    $this->Menu->addMenuItem(__('View Profile'), ['action' => 'view',$resident->id], [], 
    [
        [__('View Profile'), ['action' => 'view',$resident->id]], 
        [__('View Profile'), ['action' => 'view',$resident->id],
        [],
        [
            [__('View Profile'), ['action' => 'view',$resident->id],
            [],
            [
                [__('View Profile'), ['action' => 'view',$resident->id]], 
                [__('View Profile'), ['action' => 'view',$resident->id]]
            ]

            ], 
            [__('View Profile'), ['action' => 'view',$resident->id]]
        ]
      ]
    ]);
    
## Contact me for help at

http://devarticles.in
or  
http://inimist.com

