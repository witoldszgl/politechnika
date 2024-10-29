#include "Render.h"

// Constructor
Render::Render() {
    this->window.setFramerateLimit(30);
    // Loading font for the timer
    if (!font.loadFromFile("ARIAL.TTF")) {
        std::cerr << "Failed to load font" << std::endl;
    }
    timerText.setFont(font);
    timerText.setCharacterSize(24);
    timerText.setFillColor(sf::Color::Black);
    timerText.setPosition(10, 10);

    // Load sound files
    if (!winBuffer.loadFromFile("win.wav")) {
        std::cerr << "Failed to load win sound" << std::endl;
    }
    winSound.setBuffer(winBuffer);

    if (!loseBuffer.loadFromFile("lose.wav")) {
        std::cerr << "Failed to load lose sound" << std::endl;
    }
    loseSound.setBuffer(loseBuffer);

    // Load background music
    if (!backgroundMusic.openFromFile("background_music.wav")) {
        std::cerr << "Failed to load background music" << std::endl;
    }
    backgroundMusic.setLoop(true);
    backgroundMusic.play();

    for (int i = 0; i < rows + 1; i++) {
        std::vector<sf::Vector2f> vec1;
        std::vector<sf::RectangleShape> vec2;
        std::vector<bool> vec3;
        for (int j = 0; j < columns + 1; j++) {
            sf::Vector2f x;
            x.x = j * LENGTH / rows + OUTLINE;
            x.y = i * HEIGHT / columns + OUTLINE;
            vec1.push_back(x);
            sf::RectangleShape y;
            vec2.push_back(y);
            bool z = false;
            vec3.push_back(z);
        }
        cellPos.push_back(vec1);
        cell.push_back(vec2);
        isMine.push_back(vec3);
        isOpened.push_back(vec3);
        flag.push_back(vec3);
    }
}

void Render::rend() {
    this->window.create(sf::VideoMode(LENGTH + OUTLINE, HEIGHT + OUTLINE), "MineSweeper");

    this->window.setPosition(sf::Vector2i(0, 500));
    sf::Texture texture;
    gameClock.restart(); // Restart the clock when the game starts
    bool gameEnded = false;

    while (this->window.isOpen()) {
        int count = 0;
        this->window.clear(sf::Color::Black);
        update();  // Ensure this is correctly called
        for (int i = 0; i < rows; i++) {
            for (int j = 0; j < columns; j++) {
                this->window.draw(this->cell[i][j]);
                if (this->isOpened[i][j]) {
                    if (!this->isMine[i][j]) {
                        this->cell[i][j].setFillColor(sf::Color::White);
                        count++;
                        if (count == rows * columns - numberOfMines)
                            this->win = true;
                    }
                    texture = setNumbers(i, j);  // Ensure this is correctly called
                    sf::Sprite sprite(texture);
                    sprite.setOrigin(cell[i][j].getGlobalBounds().width / 2, cell[i][j].getGlobalBounds().height / 2);
                    sprite.setTextureRect(sf::IntRect(0, 0, this->cell[i][j].getSize().x, this->cell[i][j].getSize().y));
                    sprite.setPosition(cell[i][j].getPosition().x + cell[i][j].getGlobalBounds().width / 2, cell[i][j].getPosition().y + cell[i][j].getGlobalBounds().height / 2);
                    this->window.draw(sprite);
                }
                else if (this->flag[i][j]) {
                    if (!texture.loadFromFile("flag.png")) {
                        std::cout << "Failed to load texture" << std::endl;
                    }
                    sf::Sprite sprite(texture);
                    sprite.setOrigin(cell[i][j].getGlobalBounds().width / 2, cell[i][j].getGlobalBounds().height / 2);
                    sprite.setTextureRect(sf::IntRect(0, 0, this->cell[i][j].getSize().x, this->cell[i][j].getSize().y));
                    sprite.setPosition(cell[i][j].getPosition().x + cell[i][j].getGlobalBounds().width / 2, cell[i][j].getPosition().y + cell[i][j].getGlobalBounds().height / 2);
                    this->window.draw(sprite);
                }
                else {
                    if (!texture.loadFromFile("Blank.png")) {
                        std::cout << "Failed to load texture" << std::endl;
                    }
                    sf::Sprite sprite(texture);
                    sprite.setTextureRect(sf::IntRect(0, 0, this->cell[i][j].getSize().x, this->cell[i][j].getSize().y));
                    sprite.setPosition(cell[i][j].getPosition());
                    this->window.draw(sprite);
                }
            }
        }
        if (this->isGameOver || this->win || (isTimeAttack && gameTime.asSeconds() > timeLimit)) {
            if (!gameEnded) {
                endClock.restart();  // Restart the clock when the game ends
                gameEnded = true;
            }

            if (backgroundMusic.getStatus() == sf::Music::Playing) {
                backgroundMusic.stop();  // Stop the background music
            }
            if (this->win && !winSoundPlayed) {
                finalScore = static_cast<int>(gameTime.asSeconds()) * 10;
                winSound.play();  // Play win sound
                winSoundPlayed = true;
            }

            if ((this->isGameOver || (isTimeAttack && gameTime.asSeconds() > timeLimit)) && !loseSoundPlayed) {
                loseSound.play();  // Play lose sound
                loseSoundPlayed = true;
            }

            sf::Font font;
            font.loadFromFile("ARIAL.TTF");
            sf::Text text;
            text.setFont(font);
            text.setCharacterSize(HEIGHT / 10);
            text.setStyle(sf::Text::Bold);
            if (this->isGameOver) {
                text.setFillColor(sf::Color::Red);
                text.setString("GAME OVER!");
            }
            else if (this->win) {
                sf::Color darkGreen(0, 153, 0);
                text.setFillColor(darkGreen);
                text.setString("YOU WON\nScore: " + std::to_string(finalScore));
            }
            else if (isTimeAttack && gameTime.asSeconds() > timeLimit) {
                text.setFillColor(sf::Color::Red);
                text.setString("TIME'S UP!");
            }
            text.setOrigin(text.getGlobalBounds().width / 2, text.getGlobalBounds().height / 2);
            text.setPosition(LENGTH / 2, HEIGHT / 2 - 50);
            this->window.draw(text);

            // Check if 3 seconds have passed since the game ended
            if (gameEnded && endClock.getElapsedTime().asSeconds() > 3) {
                this->window.close();
                return;  // Exit the render loop to show the menu again
            }
        }
        else {
            updateTimer();
            this->window.draw(timerText);
        }
        this->window.display();
    }
}

void Render::update() {
    if (this->isGameOver || this->win || (isTimeAttack && gameTime.asSeconds() > timeLimit)) {
        return;  // Do not process further events if the game is over
    }

    while (this->window.pollEvent(this->event)) {
        if (this->event.type == sf::Event::Closed) {
            this->window.close();
        }
        if (this->event.type == sf::Event::MouseButtonReleased) {
            if (this->event.mouseButton.button == sf::Mouse::Left) {
                for (int i = 0; i < rows; i++) {
                    for (int j = 0; j < columns; j++) {
                        if (this->cell[i][j].getGlobalBounds().contains(this->window.mapPixelToCoords(sf::Mouse::getPosition(this->window)))) {
                            this->isOpened[i][j] = true;
                        }
                    }
                }
            }
            else if (this->event.mouseButton.button == sf::Mouse::Right) {
                for (int i = 0; i < rows; i++) {
                    for (int j = 0; j < columns; j++) {
                        if (this->cell[i][j].getGlobalBounds().contains(this->window.mapPixelToCoords(sf::Mouse::getPosition(this->window)))) {
                            if (!this->isOpened[i][j]) {
                                if (!this->flag[i][j])
                                    this->flag[i][j] = true;
                                else
                                    this->flag[i][j] = false;
                            }
                        }
                    }
                }
            }
        }
    }
}

void Render::plantMines() {
    srand(time(NULL));
    for (int i = 0; i < numberOfMines; i++) {
        int x = rand() % rows;
        int y = rand() % columns;
        if (!this->isOpened[x][y]) {
            if (this->isMine[x][y] == false) {
                this->isMine[x][y] = true;
            }
            else {
                i--;
            }
        }
        else {
            i--;
        }
    }
}

sf::Texture Render::setNumbers(int x, int y) {
    this->howManyOpen++;
    int count = 0;
    if (!this->isMine[x][y]) {
        if (x > 0) {
            if (this->isMine[x - 1][y]) {
                count++;
            }
        }
        if (x < rows - 1) {
            if (this->isMine[x + 1][y]) {
                count++;
            }
        }
        if (y > 0) {
            if (this->isMine[x][y - 1]) {
                count++;
            }
        }
        if (y < columns - 1) {
            if (this->isMine[x][y + 1]) {
                count++;
            }
        }
        if (x > 0 && y > 0) {
            if (this->isMine[x - 1][y - 1]) {
                count++;
            }
        }
        if (x < rows - 1 && y < columns - 1) {
            if (this->isMine[x + 1][y + 1]) {
                count++;
            }
        }
        if (y > 0 && x < rows - 1) {
            if (this->isMine[x + 1][y - 1]) {
                count++;
            }
        }
        if (y < columns - 1 && x > 0) {
            if (this->isMine[x - 1][y + 1]) {
                count++;
            }
        }
    }
    else {
        count += 9;
        this->isGameOver = true;
        for (int i = 0; i < rows; i++) {
            for (int j = 0; j < columns; j++) {
                if (this->isMine[i][j] == true) {
                    this->isOpened[i][j] = true;
                }
            }
        }
    }
    sf::Texture texture;
    switch (count) {
    case 0: {
        if (x > 0) {
            this->isOpened[x - 1][y] = true;
        }
        if (x < rows - 1) {
            this->isOpened[x + 1][y] = true;
        }
        if (y > 0) {
            this->isOpened[x][y - 1] = true;
        }
        if (y < columns - 1) {
            this->isOpened[x][y + 1] = true;
        }
        if (x > 0 && y > 0) {
            this->isOpened[x - 1][y - 1] = true;
        }
        if (x < rows - 1 && y < columns - 1) {
            this->isOpened[x + 1][y + 1] = true;
        }
        if (y > 0 && x < rows - 1) {
            this->isOpened[x + 1][y - 1] = true;
        }
        if (y < columns - 1 && x > 0) {
            this->isOpened[x - 1][y + 1] = true;
        }
        if (this->howManyOpen == 1) {
            this->plantMines();
        }
        break;
    }
    case 1:
        if (!texture.loadFromFile("1.png"))
            std::cout << "Failed to load texture" << std::endl;
        break;
    case 2:
        if (!texture.loadFromFile("2.png"))
            std::cout << "Failed to load texture" << std::endl;
        break;
    case 3:
        if (!texture.loadFromFile("3.png"))
            std::cout << "Failed to load texture" << std::endl;
        break;
    case 4:
        if (!texture.loadFromFile("4.png"))
            std::cout << "Failed to load texture" << std::endl;
        break;
    case 5:
        if (!texture.loadFromFile("5.png"))
            std::cout << "Failed to load texture" << std::endl;
        break;
    case 6:
        if (!texture.loadFromFile("6.png"))
            std::cout << "Failed to load texture" << std::endl;
        break;
    case 7:
        if (!texture.loadFromFile("7.png")) {
            std::cout << "Failed to load texture" << std::endl;
            break;
    case 8:
        if (!texture.loadFromFile("8.png"))
            std::cout << "Failed to load texture" << std::endl;
        break;
    case 9:
        if (!texture.loadFromFile("mine.png"))
            std::cout << "Failed to load texture" << std::endl;
        break;
    default:
        break;
        }
    }
    return texture;
}

void Render::updateTimer() {
    if (!win && !isGameOver && !(isTimeAttack && gameTime.asSeconds() > timeLimit)) { // Update timer only if the game is still ongoing and time limit not reached
        gameTime = gameClock.getElapsedTime();
        timerText.setString("Time: " + std::to_string(static_cast<int>(gameTime.asSeconds())));
    }
    if (win) {
        finalScore = static_cast<int>(gameTime.asSeconds()) * 10;
    }
}

void Render::resetClock() {
    gameClock.restart();
}
