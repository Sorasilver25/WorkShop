// Données des rappels pour chaque vaccin (en années)
const vaccinationSchedules = {
    'DTP': { interval: 10, lastVaccinationAge: 6 },  // Dernier à 6 ans, tous les 10 ans ensuite
    'Coqueluche': { interval: 10, lastVaccinationAge: 11 },  // Dernier à 11-13 ans, plus de rappels ensuite
    'Hépatite B': { interval: 0, lastVaccinationAge: 11 },  // Dernier à 11 mois, pas de rappel connu
    'Pneumocoque': { interval: 0, lastVaccinationAge: 11 },  // Dernier à 11 mois, pas de rappel connu
    'ROR': { interval: 0, lastVaccinationAge: 16 },  // Dernier à 16-18 mois
    'Méningocoque C': { interval: 0, lastVaccinationAge: 12 },  // Dernier à 12 mois
    'HPV': { interval: 0, lastVaccinationAge: 12 },  // Dernier à 12 ans
    'Grippe': { interval: 1, lastVaccinationAge: 65 },  // Annuel à partir de 65 ans
    'Covid-19': { interval: 1, lastVaccinationAge: 65 }  // Annuel à partir de 65 ans
};

// Âge actuel (à personnaliser)
const currentAge = 1;  // Par exemple 1 an

// Calculer les délais restants avant le prochain rappel
const vaccinationData = Object.keys(vaccinationSchedules).map(vaccine => {
    const { interval, lastVaccinationAge } = vaccinationSchedules[vaccine];
    
    let nextVaccinationAge = lastVaccinationAge;
    
    // Si un vaccin a un intervalle de rappel
    if (interval > 0 && currentAge >= lastVaccinationAge) {
        // Calculer la prochaine échéance en fonction de l'intervalle
        nextVaccinationAge = lastVaccinationAge + Math.ceil((currentAge - lastVaccinationAge) / interval) * interval;
    }
    
    const remainingTime = nextVaccinationAge - currentAge;
    
    return {
        x: vaccine,  // Le nom du vaccin
        y: remainingTime > 0 && remainingTime <= 15 ? remainingTime : 0  // Temps restant limité à 15 ans max
    };
});

// Configuration du graphique
const ctx = document.getElementById('scatterChart').getContext('2d');
const scatterChart = new Chart(ctx, {
    type: 'scatter',
    data: {
        datasets: [{
            label: 'Délai restant avant le rappel des vaccins',
            data: vaccinationData.map(vaccine => ({ x: vaccine.x, y: vaccine.y })),
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            pointRadius: 5
        }]
    },
    options: {
        scales: {
            x: {
                type: 'category',
                labels: ['DTP', 'Coqueluche', 'Hépatite B', 'Pneumocoque', 'ROR', 'Méningocoque C', 'HPV', 'Grippe', 'Covid-19'],
                title: {
                    display: true,
                    text: 'Vaccin'
                }
            },
            y: {
                type: 'linear',
                position: 'left',
                title: {
                    display: true,
                    text: 'Délai restant (années)'
                },
                min: 0,  // Minimum 0
                max: 15, // Maximum 15 ans
                ticks: {
                    stepSize: 3  // Intervalles de 3 ans
                }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Temps restant avant le rappel des vaccins (intervalle de 3 ans, max 15 ans)'
            }
        }
    }
});
