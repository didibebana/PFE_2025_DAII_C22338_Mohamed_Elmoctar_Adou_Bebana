// resources/js/Pages/Dashboard.jsx
import { Head } from '@inertiajs/react';
import { useState, useEffect } from 'react';

export default function Dashboard({
  tauxMoyen,
  axes,
  sousAxes,
  actionsCount,
  highestTauxAction,
  lowestTauxAction,
  totalBudget,
  totalConsomme
}) {
  // Utilisation des hooks pour la gestion de l'état si nécessaire (ex. pour des graphiques ou autres)
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Simuler un chargement de données ou des actions après le rendu initial
    setLoading(false);
  }, []);

  if (loading) {
    return (
      <div className="flex justify-center items-center h-screen">
        <div className="loader">Chargement...</div>
      </div>
    );
  }

  return (
    <div className="dashboard p-6">
      <Head title="Dashboard" />

      <h2 className="text-2xl font-semibold text-gray-800 mb-6">Tableau de Bord de l'Administration</h2>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        {/* Taux Moyen d'Exécution */}
        <div className="stat-card bg-white p-4 rounded-lg shadow-lg">
          <h3 className="text-lg font-medium text-gray-700">Taux Moyen d'Exécution</h3>
          <p className="text-xl font-bold text-indigo-600">{tauxMoyen}%</p>
        </div>

        {/* Nombre d'Actions */}
        <div className="stat-card bg-white p-4 rounded-lg shadow-lg">
          <h3 className="text-lg font-medium text-gray-700">Nombre d'Actions</h3>
          <p className="text-xl font-bold text-indigo-600">{actionsCount}</p>
        </div>

        {/* Budget Total */}
        <div className="stat-card bg-white p-4 rounded-lg shadow-lg">
          <h3 className="text-lg font-medium text-gray-700">Budget Total</h3>
          <p className="text-xl font-bold text-green-600">{totalBudget} €</p>
        </div>

        {/* Budget Consommé */}
        <div className="stat-card bg-white p-4 rounded-lg shadow-lg">
          <h3 className="text-lg font-medium text-gray-700">Budget Consommé</h3>
          <p className="text-xl font-bold text-red-600">{totalConsomme} €</p>
        </div>

      </div>

      {/* Action avec le Taux d'Exécution le plus élevé */}
      <div className="mt-6 bg-white p-4 rounded-lg shadow-lg">
        <h3 className="text-lg font-medium text-gray-700">Action avec le Taux d'Exécution le plus Élevé</h3>
        <div className="text-gray-700 mt-2">
          <h4 className="font-semibold">{highestTauxAction.nom}</h4>
          <p>{highestTauxAction.tauxExecutions && highestTauxAction.tauxExecutions[0]?.taux_execution}%</p>
        </div>
      </div>

      {/* Action avec le Taux d'Exécution le plus Bas */}
      <div className="mt-6 bg-white p-4 rounded-lg shadow-lg">
        <h3 className="text-lg font-medium text-gray-700">Action avec le Taux d'Exécution le plus Bas</h3>
        <div className="text-gray-700 mt-2">
          <h4 className="font-semibold">{lowestTauxAction.nom}</h4>
          <p>{lowestTauxAction.tauxExecutions && lowestTauxAction.tauxExecutions[0]?.taux_execution}%</p>
        </div>
      </div>

      {/* Liste des Axes */}
      <div className="mt-6 bg-white p-4 rounded-lg shadow-lg">
        <h3 className="text-lg font-medium text-gray-700">Axes Disponibles</h3>
        <ul className="mt-2 space-y-2">
          {axes.map((axe) => (
            <li key={axe.id} className="text-gray-700">{axe.nom}</li>
          ))}
        </ul>
      </div>

      {/* Liste des Sous-Axes */}
      <div className="mt-6 bg-white p-4 rounded-lg shadow-lg">
        <h3 className="text-lg font-medium text-gray-700">Sous-Axes Disponibles</h3>
        <ul className="mt-2 space-y-2">
          {sousAxes.map((sousAxe) => (
            <li key={sousAxe.id} className="text-gray-700">{sousAxe.nom}</li>
          ))}
        </ul>
      </div>

    </div>
  );
}
