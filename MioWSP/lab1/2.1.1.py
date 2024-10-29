import numpy as np


def calculate_block_probability(C, m, t, amin, amax, astep):
    results = []

    # Generowanie zakresu wartości ruchu oferowanego
    a = amin
    while a <= amax:
        # Wyznaczanie wartości ai dla każdej klasy strumienia
        ai = [(a * C) / (m * t[i]) for i in range(m)]

        # Algorytm Kaufmana-Robertsa (s[n])
        s = np.zeros(C + 1)
        s[0] = 1  # Warunek początkowy

        for n in range(1, C + 1):
            for i in range(m):
                if n >= t[i]:
                    s[n] += (ai[i] * t[i] * s[n - t[i]]) / n

        # Normalizacja
        sum_s = np.sum(s)
        p = s / sum_s

        # Obliczenie prawdopodobieństwa blokady dla każdego strumienia
        block_probabilities = []
        for i in range(m):
            E = np.sum(p[C - t[i] + 1:C + 1])
            block_probabilities.append(E)

        # Zaokrąglanie wartości a do 2 miejsc po przecinku
        results.append([round(a, 2)] + block_probabilities)

        # Zwiększenie wartości a o krok astep
        a = round(a + astep, 10)  # Użycie round zapobiega problemom z precyzją zmiennoprzecinkową

    return results


def save_results_to_file(results, C, t):
    filename = "output.txt"  # Nazwa pliku ustawiona na stałe
    with open(filename, 'w') as f:
        f.write(f"Pojemność systemu: {C}, Żądania AU: {t}\n")
        f.write("Ruch oferowany na jednostkę pojemności, Prawdopodobieństwa blokady\n")
        for row in results:
            f.write(", ".join(map(str, row)) + "\n")
    print(f"Wyniki zostały zapisane do pliku {filename}.")


# Pobieranie danych od użytkownika
C = int(input("Podaj pojemność systemu (C): "))
m = int(input("Podaj liczbę klas strumieni (m): "))

t = []
for i in range(m):
    ti = int(input(f"Podaj żądania AU dla strumienia klasy {i + 1}: "))
    t.append(ti)

amin = float(input("Podaj minimalny ruch oferowany (amin): "))
amax = float(input("Podaj maksymalny ruch oferowany (amax): "))
astep = float(input("Podaj krok obliczeń (astep): "))

# Obliczenia
results = calculate_block_probability(C, m, t, amin, amax, astep)

# Zapis wyników do pliku
save_results_to_file(results, C, t)
