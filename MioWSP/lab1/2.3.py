import numpy as np
import matplotlib.pyplot as plt


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
        a = round(a + astep, 10)

    return results


def plot_block_probability_log(results, t):
    a_values = [row[0] for row in results]

    for i in range(len(t)):
        block_probabilities = [row[i + 1] for row in results]
        plt.plot(a_values, block_probabilities, label=f'Strumień {i + 1} (t={t[i]})')

    plt.xlabel('Ruch oferowany na jednostkę pojemności (Erlang)')
    plt.ylabel('Prawdopodobieństwo blokady (skala logarytmiczna)')
    plt.yscale('log')  # Ustawienie skali logarytmicznej na osi Y
    plt.title('Prawdopodobieństwo blokady w funkcji ruchu oferowanego (skala log)')
    plt.legend()
    plt.grid(True, which="both", ls="--")

    # Zapisanie wykresu do pliku PNG
    plt.savefig("wykres2_3.png")
    plt.show()


# Parametry systemu dla zadania 2.3
C = 40  # Pojemność systemu
m = 3  # Liczba klas strumieni
t = [1, 3, 4]  # Żądania AU dla każdego strumienia

# Zakres obliczeń
amin = 0.2
amax = 1.3
astep = 0.1

# Obliczenia prawdopodobieństwa blokady
results = calculate_block_probability(C, m, t, amin, amax, astep)

# Wykres w skali logarytmicznej i zapis do pliku PNG
plot_block_probability_log(results, t)
