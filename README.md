# Readme

This is a CakePHP plugin that collects together some useful behaviors,
components and elements taken from other projects.

Note: some refactoring is currently needed to make each component more useful 
in the general case. Note also the current commit is likely broken!

## Components
* EmogrifiedEmailComponent - uses the "emogrifier" from Pelago Design to 
  apply CSS stylesheets inline for the benefit of Outlook 2007 and its ilk.

## Behaviors
* NotifiableBehavior - Sets up the notification controller to allow the 
  CakePHP email component to be used from with the model.
