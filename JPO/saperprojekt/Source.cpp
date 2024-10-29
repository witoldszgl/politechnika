#include "Render.h"
#include "Field.h"
#include <SFML/Audio.hpp>

int main() {
    while (true) {
        Field tiles;
        Render window;

        sf::RenderWindow menuWindow(sf::VideoMode(400, 800), "Difficulty Selection");
        sf::RectangleShape rect[6];
        sf::Font font;
        font.loadFromFile("ARIAL.TTF");
        sf::Text text[6];

        // Configure buttons and text
        for (int i = 0; i < 6; i++) {
            rect[i].setSize(sf::Vector2f(200, 50));
            rect[i].setOrigin(sf::Vector2f(rect[i].getGlobalBounds().width / 2, rect[i].getGlobalBounds().height / 2));
            rect[i].setPosition(sf::Vector2f(200, 150 + i * 100));
            text[i].setCharacterSize(30);
            text[i].setFillColor(sf::Color::Black);
            text[i].setFont(font);
        }

        // Set button colors
        rect[0].setFillColor(sf::Color::Green);
        rect[1].setFillColor(sf::Color::Yellow);
        rect[2].setFillColor(sf::Color::Red);
        rect[3].setFillColor(sf::Color::Blue);
        rect[4].setFillColor(sf::Color::Magenta); // Time Attack button
        rect[5].setFillColor(sf::Color::Cyan);

        // Set button text
        text[0].setString("EASY");
        text[1].setString("MEDIUM");
        text[2].setString("HARD");
        text[3].setString("CUSTOM");
        text[4].setString("TIME ATTACK");
        text[5].setString("EXIT");

        // Adjust text origin and position
        for (int i = 0; i < 6; i++) {
            text[i].setOrigin(sf::Vector2f(text[i].getGlobalBounds().width / 2, text[i].getGlobalBounds().height / 2));
            text[i].setPosition(sf::Vector2f(rect[i].getPosition().x, rect[i].getPosition().y));
        }

        bool startGame = false;
        while (menuWindow.isOpen() && !startGame) {
            sf::Event event;
            while (menuWindow.pollEvent(event)) {
                if (event.type == sf::Event::Closed) {
                    menuWindow.close();
                    return 0;  // Exit the application
                }
                if (event.type == sf::Event::MouseButtonReleased) {
                    if (event.mouseButton.button == sf::Mouse::Left) {
                        if (rect[0].getGlobalBounds().contains(menuWindow.mapPixelToCoords(sf::Mouse::getPosition(menuWindow)))) {
                            window.HEIGHT = 364.f;
                            window.LENGTH = 364.f;
                            tiles.HEIGHT = 364.f;
                            tiles.LENGTH = 364.f;
                            window.numberOfMines = 10;
                            window.columns = 8;
                            window.rows = 8;
                            tiles.columns = 8;
                            tiles.rows = 8;
                            window.resetClock();  // Reset the clock when the game mode is selected
                            startGame = true;
                        }
                        else if (rect[1].getGlobalBounds().contains(menuWindow.mapPixelToCoords(sf::Mouse::getPosition(menuWindow)))) {
                            window.HEIGHT = 727.f;
                            window.LENGTH = 727.f;
                            tiles.HEIGHT = 727.f;
                            tiles.LENGTH = 727.f;
                            window.numberOfMines = 40;
                            window.columns = 16;
                            window.rows = 16;
                            tiles.columns = 16;
                            tiles.rows = 16;
                            window.resetClock();  // Reset the clock when the game mode is selected
                            startGame = true;
                        }
                        else if (rect[2].getGlobalBounds().contains(menuWindow.mapPixelToCoords(sf::Mouse::getPosition(menuWindow)))) {
                            window.HEIGHT = 1000.f;
                            window.LENGTH = 1000.f;
                            tiles.HEIGHT = 1000.f;
                            tiles.LENGTH = 1000.f;
                            window.numberOfMines = 99;
                            window.columns = 22;
                            window.rows = 22;
                            tiles.columns = 22;
                            tiles.rows = 22;
                            window.resetClock();  // Reset the clock when the game mode is selected
                            startGame = true;
                        }
                        else if (rect[3].getGlobalBounds().contains(menuWindow.mapPixelToCoords(sf::Mouse::getPosition(menuWindow)))) {
                            menuWindow.close();
                            sf::RenderWindow customWindow(sf::VideoMode(400, 200), "Custom Mode");
                            sf::Text customText;
                            customText.setFont(font);
                            customText.setString("Enter number of mines:");
                            customText.setCharacterSize(20);
                            customText.setFillColor(sf::Color::White);
                            customText.setPosition(50, 50);

                            sf::String userInput;
                            sf::Text userInputText;
                            userInputText.setFont(font);
                            userInputText.setCharacterSize(20);
                            userInputText.setFillColor(sf::Color::White);
                            userInputText.setPosition(50, 100);

                            while (customWindow.isOpen()) {
                                sf::Event customEvent;
                                while (customWindow.pollEvent(customEvent)) {
                                    if (customEvent.type == sf::Event::Closed)
                                        customWindow.close();
                                    if (customEvent.type == sf::Event::TextEntered) {
                                        if (customEvent.text.unicode >= 48 && customEvent.text.unicode <= 57) {
                                            userInput += customEvent.text.unicode;
                                        }
                                        else if (customEvent.text.unicode == 8 && !userInput.isEmpty()) {
                                            userInput.erase(userInput.getSize() - 1, 1);
                                        }
                                        userInputText.setString(userInput);
                                    }
                                    if (customEvent.type == sf::Event::KeyPressed && customEvent.key.code == sf::Keyboard::Return) {
                                        if (!userInput.isEmpty()) {
                                            window.numberOfMines = std::stoi(userInput.toAnsiString());
                                            window.HEIGHT = 1000.f;
                                            window.LENGTH = 1000.f;
                                            tiles.HEIGHT = 1000.f;
                                            tiles.LENGTH = 1000.f;
                                            window.columns = 22;
                                            window.rows = 22;
                                            tiles.columns = 22;
                                            tiles.rows = 22;
                                            window.resetClock();  // Reset the clock when the game mode is selected
                                            customWindow.close();
                                            startGame = true;
                                        }
                                    }
                                }
                                customWindow.clear();
                                customWindow.draw(customText);
                                customWindow.draw(userInputText);
                                customWindow.display();
                            }
                        }
                        else if (rect[4].getGlobalBounds().contains(menuWindow.mapPixelToCoords(sf::Mouse::getPosition(menuWindow)))) {
                            window.HEIGHT = 727.f;
                            window.LENGTH = 727.f;
                            tiles.HEIGHT = 727.f;
                            tiles.LENGTH = 727.f;
                            window.numberOfMines = 40;
                            window.columns = 16;
                            window.rows = 16;
                            tiles.columns = 16;
                            tiles.rows = 16;
                            window.resetClock();  // Reset the clock when the game mode is selected
                            window.isTimeAttack = true;  // Enable Time Attack mode
                            startGame = true;
                        }
                        else if (rect[5].getGlobalBounds().contains(menuWindow.mapPixelToCoords(sf::Mouse::getPosition(menuWindow)))) {
                            menuWindow.close();
                            return 0;
                        }
                    }
                }
            }
            menuWindow.clear();
            for (int i = 0; i < 6; i++) {
                menuWindow.draw(rect[i]);
                menuWindow.draw(text[i]);
            }
            menuWindow.display();
        }

        if (startGame) {
            menuWindow.close();  // Ensure the menu window is closed before starting the game
            window.cellPos = tiles.tilesPos;
            window.cell = tiles.tiles;
            window.rend();
        }
    }

    return 0;
}
