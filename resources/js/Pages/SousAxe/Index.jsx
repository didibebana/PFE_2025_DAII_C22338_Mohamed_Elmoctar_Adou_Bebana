import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import SousAxesTAble from './SousAxesTable';
import { usePage } from '@inertiajs/react';

export default function Index({ auth, sousaxes, queryParams = null, success }) {

    const user = usePage().props.auth.user;

    const isAdmin = user?.role?.nom === 'administrateur';
    const isCoordinateur = user?.role?.nom === 'coordinateur_axe';
    const isResponsable = user?.role?.nom === 'responsable_action';

    return(
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Liste des Sous-Axes
                    </h2>{(isAdmin || isCoordinateur) && (<>
                    <Link href={route('sousaxe.create')} className="bg-emerald-500 text-white font-bold py-1 px-3 rounded shadow transition-all hover:bg-emerald-400">Ajouter un Sous-Axe</Link>
                    </>)}
                </div>
            }
        >

            <Head title="Sous-Axes" />
            {success &&
                (<div className="bg-emerald-500 py-2 px-4 text-white rounded">
                    {success}
                </div>)
            }

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <SousAxesTAble sousaxes={sousaxes} queryParams={queryParams} />
                </div>
            </div>
        </AuthenticatedLayout>
    )

}
