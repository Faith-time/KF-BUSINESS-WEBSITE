import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import InvestisseurLayout from '@/layouts/investisseur-layout';
import { InvestorProjects } from '@/screens/investor';

export default function Page() {
  return (
    <>
      <Head title="Projets" />
      <InvestorProjects />
    </>
  );
}

Page.layout = (page: ReactNode) => <InvestisseurLayout>{page}</InvestisseurLayout>;
