#pragma once

#include <SFML/Graphics.hpp>
#include <iostream>
#include <vector>

class Field
{
public:
    int rows = 22;
    int columns = 22;
    int numberOfMines = 99;
    float HEIGHT = 1000.f;    // height of the screen
    float LENGTH = 1000.f;    // width of the screen
    float OUTLINE = 3.f;      // gaps around individual cells
    Field();
    void setPos();
    void setTiles();
    std::vector<std::vector<sf::Vector2f>> tilesPos;
    std::vector<std::vector<sf::RectangleShape>> tiles;
};
