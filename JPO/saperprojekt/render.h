#pragma once

#include <SFML/Graphics.hpp>
#include <SFML/Audio.hpp>
#include <iostream>
#include <vector>

class Render
{
private:
    sf::RenderWindow window;
    sf::Event event;
    bool isGameOver = false;
    bool win = false;
    int howManyOpen = 0;
    sf::Clock gameClock;
    sf::Clock endClock; // Clock to track time after game ends
    sf::Time gameTime;
    sf::Font font;
    sf::Text timerText;
    int finalScore = 0;
    const float timeLimit = 300.0f;  // Time limit for Time Attack mode set to 300 seconds

    // Sound buffers and sounds
    sf::SoundBuffer winBuffer;
    sf::SoundBuffer loseBuffer;
    sf::Sound winSound;
    sf::Sound loseSound;

    // Background music
    sf::Music backgroundMusic;

    // Flags to track if sounds have been played
    bool winSoundPlayed = false;
    bool loseSoundPlayed = false;

public:
    int rows = 22;
    int columns = 22;
    int numberOfMines = 99;
    float HEIGHT = 1000.f;   // height of the screen
    float LENGTH = 1000.f;   // width of the screen
    float OUTLINE = 3.f;     // gaps around individual cells
    bool isTimeAttack = false;  // Flag for Time Attack mode

    std::vector<std::vector<sf::Vector2f>> cellPos;
    std::vector<std::vector<sf::RectangleShape>> cell;
    std::vector<std::vector<bool>> isMine;
    std::vector<std::vector<bool>> isOpened;
    std::vector<std::vector<bool>> flag;

    Render();
    void rend();
    void update();
    void plantMines();
    sf::Texture setNumbers(int x, int y);
    void updateTimer();
    void resetClock();
};
