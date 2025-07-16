import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import {
    BarChart, Bar, XAxis, YAxis, Tooltip, CartesianGrid,
    ResponsiveContainer, PieChart, Pie, Cell
} from 'recharts';

const Card = ({ title, value, className = '' }) => (
    <div className={`bg-white dark:bg-gray-800 rounded-lg p-4 shadow ${className}`}>
        <h4 className="text-sm text-gray-500 dark:text-gray-400">{title}</h4>
        <p className="text-2xl font-bold mt-2 dark:text-white">{value}</p>
    </div>
);

const BudgetProgress = ({ consumed, total }) => {
    const percentage = total > 0 ? Math.round((consumed / total) * 100) : 0;

    return (
        <div className="mt-4">
            <div className="flex justify-between text-sm mb-1">
                <span className="text-gray-600 dark:text-gray-300">Budget utilisé</span>
                <span className="font-medium">{percentage}%</span>
            </div>
            <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                <div
                    className="bg-green-500 h-2.5 rounded-full"
                    style={{ width: `${percentage}%` }}
                ></div>
            </div>
            <div className="flex justify-between text-xs mt-1 text-gray-500 dark:text-gray-400">
                <span>{consumed.toLocaleString('fr-FR')} DA</span>
                <span>{total.toLocaleString('fr-FR')} DA</span>
            </div>
        </div>
    );
};

export default function Dashboard({
    auth,
    axesCount,
    sousAxesCount,
    actionsCount,
    usersCount,
    budgetTotal,
    budgetConsomme,
    tauxExecutionMoyen,
    executionParAxe,
    usersByRole,
    actionsCouteuses,
    actionsEnRetard,
    logs,
}) {
    const budgetData = [
        { name: 'Consommé', value: budgetConsomme },
        { name: 'Restant', value: budgetTotal - budgetConsomme },
    ];

    const COLORS = ['#10B981', '#EF4444'];

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Tableau de Bord
                </h2>
            }
        >
            <Head title="Tableau de Bord" />

            <div className="py-6">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <Card title="Axes stratégiques" value={(axesCount || 0).toLocaleString('fr-FR')} />
                    <Card title="Sous-Axes" value={(sousAxesCount || 0).toLocaleString('fr-FR')} />
                    <Card title="Actions" value={(actionsCount || 0).toLocaleString('fr-FR')} />
                    <Card title="Utilisateurs" value={(usersCount || 0).toLocaleString('fr-FR')} />
                </div>


                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <Card
                            title="Budget Total"
                            value={`${(budgetTotal || 0).toLocaleString()} UM`}
                            className="relative"
                        >
                            <BudgetProgress
                                consumed={budgetConsomme || 0}
                                total={budgetTotal || 0}
                            />
                        </Card>

                        <Card
                            title="Taux d'exécution moyen"
                            value={`${(tauxExecutionMoyen || 0)}%`}
                        />
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <h3 className="text-lg font-semibold mb-4 dark:text-white">Utilisateurs par rôle</h3>
                        <ul className="space-y-1 text-gray-700 dark:text-gray-300">
                            {usersByRole.map((role, index) => (
                                <li key={index}>
                                    <h4>Le nombre d'utilisateur ont le rôle </h4><strong>{role.role}</strong> : {role.count}
                                </li>
                            ))}
                        </ul>
                    </div>
                    <div className="bg-white dark:bg-gray-800 p-4 rounded shadow">
                        <h3 className="font-semibold text-lg mb-2 dark:text-white">🕒 Historique</h3>
                        <ul className="text-sm">
                            {(logs || []).map((log, index) => (
                                <li key={index}>
                                    [{new Date(log.created_at).toLocaleString()}] {log.username || log.email} - {log.description}
                                </li>
                            ))}
                        </ul>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div className="bg-white dark:bg-gray-800 p-4 rounded shadow">
                            <h3 className="font-semibold text-lg mb-2 dark:text-white">🔴 Actions en retard</h3>
                            <ul className="text-sm">
                                {actionsEnRetard.map(action => (
                                    <li key={action.id}>
                                        {action.nom} - <span className="text-red-500">{new Date(action.date_fin).toLocaleDateString()}</span>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        <div className="bg-white dark:bg-gray-800 p-4 rounded shadow">
                            <h3 className="font-semibold text-lg mb-2 dark:text-white">💰 Actions les plus coûteuses</h3>
                            <ul className="text-sm">
                                {actionsCouteuses.map(action => (
                                    <li key={action.id}>
                                        {action.nom} - {(action.budget?.montant || 0).toLocaleString()} UM
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>


                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <h3 className="text-lg font-semibold mb-4 dark:text-white">Répartition du budget</h3>
                        <div className="h-64">
                            <ResponsiveContainer width="100%" height="100%">
                                <PieChart>
                                    <Pie
                                        data={budgetData}
                                        cx="50%"
                                        cy="50%"
                                        labelLine={false}
                                        outerRadius={80}
                                        fill="#8884d8"
                                        dataKey="value"
                                    >
                                        {budgetData.map((entry, index) => (
                                            <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                                        ))}
                                    </Pie>
                                    <Tooltip />
                                </PieChart>
                            </ResponsiveContainer>
                        </div>
                    </div>


                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <h3 className="text-lg font-semibold mb-4 dark:text-white">Taux d'exécution par axe</h3>
                        <div className="h-64">
                            <ResponsiveContainer width="100%" height="100%">
                                <BarChart data={executionParAxe} margin={{ top: 20, right: 30, left: 20, bottom: 5 }}>
                                    <CartesianGrid strokeDasharray="3 3" />
                                    <XAxis dataKey="axe" />
                                    <YAxis />
                                    <Tooltip formatter={(value) => `${value.toLocaleString('fr-FR')}%`} />
                                    <Bar dataKey="moyenne" fill="#3B82F6" />
                                </BarChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
