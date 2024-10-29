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


def calculate_avg_requests(C, m, t, amin, amax, astep):
    avg_results = []

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

        # Obliczenie średniej liczby zgłoszeń w każdym stanie zajętości
        avg_requests = []
        for n in range(C + 1):
            avg_n = []
            total_resources = 0
            for i in range(m):
                if n >= t[i]:
                    yi_n = (ai[i] * t[i] * p[n - t[i]]) / p[n]
                else:
                    yi_n = 0
                avg_n.append(round(yi_n, 2))
                total_resources += yi_n
            avg_n.append(round(total_resources, 2))  # Sumaryczna wartość zajętych zasobów
            avg_requests.append([n] + avg_n)

        avg_results.append([round(a, 2), avg_requests])

        # Zwiększenie wartości a o krok astep
        a = round(a + astep, 10)

    return avg_results


def save_avg_requests_to_file(avg_results, C, t):
    filename = "output_avg_requests.txt"  # Nazwa pliku ustawiona na stałe
    with open(filename, 'w') as f:
        f.write(f"Pojemność systemu: {C}, Żądania AU: {t}\n")
        f.write("Ruch oferowany na jednostkę pojemności, Średnia liczba zgłoszeń w stanach zajętości\n")

        for result in avg_results:
            a_value = result[0]
            avg_requests = result[1]

            f.write(f"\nRuch oferowany: {a_value}\n")
            f.write("Stan zajętości, ")
            f.write(", ".join([f"Strumień {i + 1}" for i in range(len(t))]))
            f.write(", Sumaryczna liczba zasobów\n")

            for row in avg_requests:
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

# Obliczenia dla średniej liczby zgłoszeń
avg_results = calculate_avg_requests(C, m, t, amin, amax, astep)

# Zapis wyników do pliku
save_avg_requests_to_file(avg_results, C, t)
