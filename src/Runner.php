<?php
namespace Punch;

use Punch\Output;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Exception\WebDriverException;

class Runner
{
    private $steps = [];
    private $driver;
    private $currentElement;
    private $currentElements = [];
    
    public function __construct($steps, $driver)
    {
        $this->steps = $steps;
        $this->driver = $driver;
    }

    public function processSteps()
    {
        $persistElement = false;
        foreach ($this->steps as $name => $step) {
            if (CONFIG['debug']) {
                Output::print('DEBUG', $name);
            }

            $checkError = (!empty($step['checkErrors']) && is_array($step['checkErrors']));

            $hasClick = (!empty($step['click']) && $step['click'] === true);

            $hasSendKeys = (!empty($step['sendKeys']));
            
            if ($checkError || $hasClick || $hasSendKeys) {
                $persistElement = true;
            } else {
                $persistElement = false;
            }

            foreach ($step as $action => $data) {
                if ($action === 'checkErrors') {
                    $message = $step['checkErrors']['success']['message'];
                    $printMessage = !empty($step['checkErrors']['success']['print']) ? $step['checkErrors']['success']['print'] : false;
                    if ($this->currentElement !== null) { // single element
                        $message = $this->currentElement->getText();
                        $this->currentElement = null;
                        $action = 'error';
                    } elseif (count($this->currentElements)) { // array of elements
                        $message = $step['checkErrors']['error']['message'];
                        foreach ($this->currentElements as $element) {
                            $message .= ' ' . $element->getText();
                        }
                        $this->currentElements = [];
                        $action = 'error';
                    } else {
                        // no elements saved to check, assumes no errors
                        $action = 'success';
                    }
                }

                try {
                    switch ($action) {
                        case 'findElement':
                            $selector = (!empty($data[1]) && $data[1] === 'cssSelector')
                                ? WebDriverBy::cssSelector($data[0])
                                : WebDriverBy::id($data[0]);
                            $element = $this->driver->findElement($selector);
                            $this->currentElement = $persistElement ? $element : null;
                            break;
                        case 'findElements':
                            $elements = $this->driver->findElements(
                                WebDriverBy::cssSelector($data)
                            );
                            $this->currentElements = $persistElement ? $elements : [];
                            break;
                        case 'sendKeys':
                            $this->currentElement->sendKeys($data);
                            $this->currentElement = null;
                            break;
                        case 'click':
                            $this->currentElement->click();
                            $this->currentElement = null;
                            break;
                        case 'waitUntilLocated':
                            try {
                                $timeToWait = (!empty($data[2]))
                                    ? $data[2]
                                    : 10; // seconds

                                $selector = (!empty($data[1]) && $data[1] === 'cssSelector')
                                    ? WebDriverBy::cssSelector($data[0])
                                    : WebDriverBy::id($data[0]);

                                $this->driver->wait($timeToWait, 1)->until(
                                    WebDriverExpectedCondition::presenceOfElementLocated(
                                        $selector
                                    )
                                );
                            } catch (\Throwable $th) {
                                if (CONFIG['debug']) {
                                    Output::print('DEBUG', 'No Elements found');
                                }
                            }
                            break;
                        case 'waitToBeClicked':
                            $timeToWait = (!empty($data[2]))
                                ? $data[2]
                                : 10; // seconds
                            $selector = (!empty($data[1]) && $data[1] === 'cssSelector')
                                ? WebDriverBy::cssSelector($data[0])
                                : WebDriverBy::id($data[0]);
                            $element = $this->driver->wait($timeToWait, 1000)->until(
                                WebDriverExpectedCondition::elementToBeClickable($selector)
                            );
                            $element->click();
                            break;
                        case 'waitUntilInvisible':
                            $timeToWait = (!empty($data[2]))
                                ? $data[2]
                                : 10; // seconds
                            $selector = (!empty($data[1]) && $data[1] === 'cssSelector')
                                ? WebDriverBy::cssSelector($data[0])
                                : WebDriverBy::id($data[0]);
                            $this->driver->wait($timeToWait, 1000)->until(
                                WebDriverExpectedCondition::invisibilityOfElementLocated($selector)
                            );
                            break;
                        case 'success':
                            if (CONFIG['debug']) {
                                Output::print('DEBUG', $message);
                            }

                            // only set inside the $step['checkErrors']['success']['print'] 
                            if ($printMessage) {
                                Output::print($message, '');
                            }
                            return true;
                            break;
                        case 'error':
                            Output::print('ERROR', $message);
                            return false;
                            break;
                        default:
                            break;
                    }
                } catch (WebDriverException $th) {
                    $message = !empty($step['checkErrors']['error']['message'])
                    ? $step['checkErrors']['error']['message']
                    : $th->getMessage();
                    Output::print('ERROR', $message);
                    return false;
                } catch (\Throwable $th) {
                    throw $th;
                }
            }
        }
    }
}