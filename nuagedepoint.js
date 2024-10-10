
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
let currentAge = 1;  // Par exemple 1 an

fetch('convert2xml.php') // Appel du fichier PHP qui renvoie les données JSON
.then(response => response.json())
.then(data => {
                // Affiche le JSON formaté dans l'area
                document.getElementById('jsonArea').value = JSON.stringify(data, null, 4);

        function getCurrentDate() {
            const today = new Date();
            const day = String(today.getDate()).padStart(2, '0'); // Ajouter un zéro devant si nécessaire
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
            const year = today.getFullYear();
            return `${day}/${month}/${year}`;
        }

        function parseDate(dateString) {
            const parts = dateString.split('/'); // Sépare la chaîne par le caractère '/'
            const day = parseInt(parts[0], 10); // Récupère le jour
            const month = parseInt(parts[1], 10) - 1; // Récupère le mois (0-11, donc on soustrait 1)
            const year = parseInt(parts[2], 10); // Récupère l'année
        
            return new Date(year, month, day); // Crée et retourne l'objet Date
        }

        function calculateAge(currentDate, birthDate) {
            console.error("Date de naissance :", birthDate);
            const birth = parseDate(birthDate); // Convertir la date de naissance en objet Date
            const today = new Date(currentDate); // Utiliser la date du jour fournie

            console.error(birth,birthDate, today);
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
        
            // Ajuster l'âge si la date d'anniversaire n'est pas encore passée cette année
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }
            console.error("Âge calculé :", age);
            return age;
        }
        
        // Exemple d'utilisation
        const currentDate = new Date(); // Récupérer la date du jour

        let dateNaissance = 0;
    data.forEach((value, index) => {
    // Vérifie si la valeur n'est pas vide
    if (value !== "") {
        switch (value) {
            case "Date de naissance":
                // Récupérer la valeur de l'index + 1
                if (index + 1 < data.length) {
                    dateNaissance = data[index + 1];
                    console.error(dateNaissance)
                }

                    
        let age = calculateAge(currentDate, dateNaissance);
        console.error(`Âge : ${age} ans`);

        if (age >= 1 ){
            currentAge = age;
        }
                break;
            }
        }
    })

   
    
    console.error(currentAge)
})
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
            backgroundColor: 'rgb(0,123,255)',
            borderColor: 'rgba(0, 0, 0, 1)',
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
