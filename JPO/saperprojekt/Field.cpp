#include "Field.h"

Field::Field()
{
    Field::setPos();
    Field::setTiles();
}

void Field::setPos()
{
    for (int i = 0; i < this->rows + 1; i++) {
        std::vector<sf::Vector2f> vec;
        for (int j = 0; j < this->columns + 1; j++) {
            sf::Vector2f x;
            x.x = j * LENGTH / this->rows + OUTLINE;
            x.y = i * HEIGHT / this->columns + OUTLINE;
            vec.push_back(x);
        }
        this->tilesPos.push_back(vec);
    }
}

void Field::setTiles()
{
    for (int i = 0; i < this->rows + 1; i++) {
        std::vector<sf::RectangleShape> vec;
        for (int j = 0; j < this->columns + 1; j++) {
            sf::RectangleShape x;
            x.setSize(sf::Vector2f(LENGTH / this->rows - OUTLINE, HEIGHT / this->columns - OUTLINE));
            x.setPosition(this->tilesPos[i][j]);
            sf::Color gray(128, 128, 128);
            x.setFillColor(gray);

            vec.push_back(x);
        }
        this->tiles.push_back(vec);
    }
}
