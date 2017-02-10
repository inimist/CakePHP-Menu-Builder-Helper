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
    $this->Menu->addItem(__('First Link'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addItem(__('Second Link'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addSubItem(__('Second Sub Link One'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addSubItem(__('Second Sub Link Two'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addItem(__('Third Link'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addSubItem(__('Third Sub Link One'), ['action' => 'linkaction', $otherparams]);
    $this->Menu->addSubItem(__('Third Sub Link Two'), ['action' => 'linkaction', $otherparams]);

## Call menu in your Layout, for example default.ctp

    echo $this->Menu->get('main');// optional argument 'main', 
    //in case of multiple menus. Just echo $this->Menu->get() for default is main.

That's it!

## Advanced Options

### Add Multi-level menu in single AddItem call

    $this->Menu->addItem(__('View Profile'), ['action' => 'view',$entity->id], [], 
    [
        [__('View Profile'), ['action' => 'view',$entity->id]], 
        [__('View Profile'), ['action' => 'view',$entity->id],
        [],
        [
            [__('View Profile'), ['action' => 'view',$entity->id],
            [],
            [
                [__('View Profile'), ['action' => 'view',$entity->id]], 
                [__('View Profile'), ['action' => 'view',$entity->id]]
            ]

            ], 
            [__('View Profile'), ['action' => 'view',$entity->id]]
        ]
      ]
    ]);

Hint: $entity is for example purpose only

### Adding postLink

    <?php $this->Menu->addSubItem(
      $this->Form->postLink(
        __('Delete'),
        ['action' => 'delete', $entity->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $entity->id)]
      ), 
      '#', 
      ['escape'=>false]); 
    ?>


## Contact me for any support or help through

http://devarticles.in/contact
or  
http://inimist.com/contact-us/

